<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idProd = null;

    #[ORM\Column(length: 255)]
    private ?string $nameProd = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imgProd = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numeroProd = null;

    #[ORM\Column(type: 'float')]
    private ?float $priceProd = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripProd = null;

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $galleryProd = null;

    public function getIdProd(): ?int
    {
        return $this->idProd;
    }

    public function getNameProd(): ?string
    {
        return $this->nameProd;
    }

    public function setNameProd(string $nameProd): self
    {
        $this->nameProd = $nameProd;
        return $this;
    }

    public function getImgProd(): ?string
    {
        return $this->imgProd;
    }

    public function setImgProd(?string $imgProd): self
    {
        $this->imgProd = $imgProd;
        return $this;
    }

    public function getNumeroProd(): ?string
    {
        return $this->numeroProd;
    }

    public function setNumeroProd(?string $numeroProd): self
    {
        $this->numeroProd = $numeroProd;
        return $this;
    }

    public function getPriceProd(): ?float
    {
        return $this->priceProd;
    }

    public function setPriceProd(float $priceProd): self
    {
        $this->priceProd = $priceProd;
        return $this;
    }

    public function getDescripProd(): ?string
    {
        return $this->descripProd;
    }

    public function setDescripProd(?string $descripProd): self
    {
        $this->descripProd = $descripProd;
        return $this;
    }

    public function getGalleryProd(): ?array
    {
        return $this->galleryProd;
    }

    public function setGalleryProd(?array $galleryProd): self
    {
        $this->galleryProd = $galleryProd;
        return $this;
    }
}
