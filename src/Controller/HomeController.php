<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductForm;
use App\Form\ProductType;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em,
        FileUploader $fileUploader
    ): Response {
        $product = new Product();

        $form = $this->createForm(ProductForm::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer le numéro produit pour créer un sous-dossier
            $numeroProd = $form->get('numeroProd')->getData();

            // 1) Upload image principale dans dossier 'images_produit/{numeroProd}'
            $imageFile = $form->get('imgProd')->getData();
            if ($imageFile) {
                try {
                    $newFilename = $fileUploader->upload($imageFile, 'images_produit', $numeroProd);
                    $product->setImgProd($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image principale.');
                    return $this->render('home/index.html.twig', [
                        'form' => $form->createView(),
                        'products' => $em->getRepository(Product::class)->findAll(),
                    ]);
                }
            }

            // 2) Upload galerie multiple dans dossier 'galleries_img/{numeroProd}'
            /** @var UploadedFile[]|null $galleryFiles */
            $galleryFiles = $form->get('galleryProd')->getData();

            $galleryFilenames = [];
            if ($galleryFiles) {
                foreach ($galleryFiles as $file) {
                    if ($file) {
                        try {
                            $galleryFilenames[] = $fileUploader->upload($file, 'galleries_img', $numeroProd);
                        } catch (FileException $e) {
                            $this->addFlash('error', 'Erreur lors de l\'upload d\'une image de la galerie.');
                            return $this->render('home/index.html.twig', [
                                'form' => $form->createView(),
                                'products' => $em->getRepository(Product::class)->findAll(),
                            ]);
                        }
                    }
                }
            }

            if (!empty($galleryFilenames)) {
                $product->setGalleryProd($galleryFilenames);
            }

            // Persister et flush en base
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produit ajouté avec succès !');

            return $this->redirectToRoute('home');
        }

        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'products' => $products,
        ]);
    }
}
