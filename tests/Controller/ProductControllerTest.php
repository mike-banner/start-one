<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $productRepository;
    private string $path = '/product/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->productRepository = $this->manager->getRepository(Product::class);

        foreach ($this->productRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'product[nameProd]' => 'Testing',
            'product[imgProd]' => 'Testing',
            'product[numeroProd]' => 'Testing',
            'product[priceProd]' => 'Testing',
            'product[descripProd]' => 'Testing',
            'product[galleryProd]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->productRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setNameProd('My Title');
        $fixture->setImgProd('My Title');
        $fixture->setNumeroProd('My Title');
        $fixture->setPriceProd('My Title');
        $fixture->setDescripProd('My Title');
        $fixture->setGalleryProd('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Product');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setNameProd('Value');
        $fixture->setImgProd('Value');
        $fixture->setNumeroProd('Value');
        $fixture->setPriceProd('Value');
        $fixture->setDescripProd('Value');
        $fixture->setGalleryProd('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'product[nameProd]' => 'Something New',
            'product[imgProd]' => 'Something New',
            'product[numeroProd]' => 'Something New',
            'product[priceProd]' => 'Something New',
            'product[descripProd]' => 'Something New',
            'product[galleryProd]' => 'Something New',
        ]);

        self::assertResponseRedirects('/product/');

        $fixture = $this->productRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNameProd());
        self::assertSame('Something New', $fixture[0]->getImgProd());
        self::assertSame('Something New', $fixture[0]->getNumeroProd());
        self::assertSame('Something New', $fixture[0]->getPriceProd());
        self::assertSame('Something New', $fixture[0]->getDescripProd());
        self::assertSame('Something New', $fixture[0]->getGalleryProd());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Product();
        $fixture->setNameProd('Value');
        $fixture->setImgProd('Value');
        $fixture->setNumeroProd('Value');
        $fixture->setPriceProd('Value');
        $fixture->setDescripProd('Value');
        $fixture->setGalleryProd('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/product/');
        self::assertSame(0, $this->productRepository->count([]));
    }
}
