<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\File\UploadedFile;
final class ProductControllerTest extends WebTestCase
{
    private \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;
    private EntityManagerInterface $manager;
    private \Doctrine\Persistence\ObjectRepository $productRepository;
    private string $path = '/product';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->productRepository = $this->manager->getRepository(Product::class);

        // Nettoyage de la base avant chaque test
        foreach ($this->productRepository->findAll() as $object) {
            $this->manager->remove($object);
        }
        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->request('GET', $this->path);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertPageTitleContains('Produit'); // dépend de <title> dans le template
    }

    public function testNew(): void
    {
        $this->client->followRedirects(); // pour suivre la redirection après submit
        $this->client->request('GET', $this->path . '/' . 'new');

        self::assertResponseIsSuccessful();


        $this->client->submitForm('Enregistrer', [
            'product_form[nameProd]' => 'Test produit',
            'product_form[imgProd]' => new UploadedFile(
                __DIR__.'/../Fixtures/test.jpg', // mets un vrai fichier de test ici
                'modif.jpg'
            ),
            'product_form[numeroProd]' => 'REF123',
            'product_form[priceProd]' => 25.5,
            'product_form[descripProd]' => 'Description test',
            'product_form[galleryProd]' => [
                new UploadedFile(__DIR__.'/../Fixtures/test.jpg', 'gal-modif.jpg')
    ],
        ]);

        self::assertResponseIsSuccessful();
        self::assertSame(1, $this->productRepository->count([]));
    }

    public function testShow(): void
    {
        $this->client->followRedirects();

        $product = new Product();
        $product->setNameProd('Produit à voir');
        $product->setImgProd('img.jpg');
        $product->setNumeroProd('NUM456');
        $product->setPriceProd(42.0);
        $product->setDescripProd('Description');
        $product->setGalleryProd(['gal1.jpg']);

        $this->manager->persist($product);
        $this->manager->flush();

        $this->client->request('GET', $this->path . '/' . $product->getIdProd());
        self::assertResponseIsSuccessful();
        self::assertPageTitleContains('Produit');
    }

    public function testEdit(): void
    {
        $this->client->followRedirects();

        $product = new Product();
        $product->setNameProd('Ancien nom');
        $product->setImgProd('img.png');
        $product->setNumeroProd('NUM789');
        $product->setPriceProd(12.34);
        $product->setDescripProd('Ancienne description');
        $product->setGalleryProd(['gal.png']);

        $this->manager->persist($product);
        $this->manager->flush();

        $this->client->request('GET', $this->path . '/' . $product->getIdProd() . '/edit');

        $this->client->submitForm('Enregistrer', [ // même nom que dans ton formulaire
            'product_form[nameProd]' => 'Nom modifié',
            'product_form[imgProd]' => new UploadedFile(
                __DIR__.'/../Fixtures/test.jpg', // mets un vrai fichier de test ici
                'modif.jpg'
            ),
            'product_form[numeroProd]' => 'NUM000',
            'product_form[priceProd]' => 99.99,
            'product_form[descripProd]' => 'Nouvelle description',
           'product_form[galleryProd]' => [
                new UploadedFile(__DIR__.'/../Fixtures/test.jpg', 'gal-modif.jpg')
    ],
        ]);

        self::assertResponseIsSuccessful();
        

        $productUpdated = $this->productRepository->find($product->getIdProd());

        self::assertSame('Nom modifié', $productUpdated->getNameProd());
self::assertSame('test.jpg', $productUpdated->getImgProd());        self::assertSame('NUM000', $productUpdated->getNumeroProd());
        self::assertSame(99.99, $productUpdated->getPriceProd());
        self::assertSame('Nouvelle description', $productUpdated->getDescripProd());
self::assertSame(['test.jpg'], $productUpdated->getGalleryProd());    }

    public function testRemove(): void
    {
        $this->client->followRedirects();

        $product = new Product();
        $product->setNameProd('À supprimer');
        $product->setImgProd('del.jpg');
        $product->setNumeroProd('DEL123');
        $product->setPriceProd(13.5);
        $product->setDescripProd('Description');
        $product->setGalleryProd(['gal.jpg']);

        $this->manager->persist($product);
        $this->manager->flush();

        $this->client->request('GET', $this->path . '/' . $product->getIdProd());
        $this->client->submitForm('Delete'); // à adapter selon le nom exact du bouton

        self::assertResponseIsSuccessful();
        self::assertSame(0, $this->productRepository->count([]));
    }
}
