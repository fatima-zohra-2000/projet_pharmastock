<?php

namespace App\Entity;

use App\Repository\StockRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StockRepository::class)]
class Stock
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(fetch: 'EAGER', inversedBy: 'stock')]
    #[ORM\JoinColumn(name: "produit_id", referencedColumnName: "id", nullable: false)]
    private ?Produit $produit_id = null;

    #[ORM\ManyToOne(inversedBy: 'stocks')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Fournisseur $fournisseur_id = null;

    #[ORM\Column]
    private ?int $quantite = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitId(): ?Produit
    {
        return $this->produit_id;
    }

    public function setProduitId(Produit $produit_id): self
    {
        $this->produit_id = $produit_id;

        return $this;
    }

    public function getFournisseurId(): ?Fournisseur
    {
        return $this->fournisseur_id;
    }

    public function setFournisseurId(?Fournisseur $fournisseur_id): self
    {
        $this->fournisseur_id = $fournisseur_id;

        return $this;
    }

    public function getQuantite(): ?int
    {
        return $this->quantite;
    }

    public function setQuantite(int $quantite): self
    {
        $this->quantite = $quantite;

        return $this;
    }
}
