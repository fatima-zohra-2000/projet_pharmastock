<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_commande = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column]
    private ?float $TVA = null;

    #[ORM\Column]
    private ?float $mantant_TVA = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'commande', targetEntity: TailleCommande::class, cascade:['persist'])]
    private Collection $tailleCommandes;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Client $client = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?User $user = null;

    public function __construct()
    {
        $this->tailleCommandes = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCommande(): ?int
    {
        return $this->num_commande;
    }

    public function setNumCommande(int $num_commande): self
    {
        $this->num_commande = $num_commande;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

        return $this;
    }

    public function getTVA(): ?float
    {
        return $this->TVA;
    }

    public function setTVA(float $TVA): self
    {
        $this->TVA = $TVA;

        return $this;
    }

    public function getMantantTVA(): ?float
    {
        return $this->mantant_TVA;
    }

    public function setMantantTVA(float $mantant_TVA): self
    {
        $this->mantant_TVA = $mantant_TVA;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, TailleCommande>
     */
    public function getTailleCommandes(): Collection
    {
        return $this->tailleCommandes;
    }

    public function addTailleCommande(TailleCommande $tailleCommande): self
    {
        if (!$this->tailleCommandes->contains($tailleCommande)) {
            $this->tailleCommandes->add($tailleCommande);
            $tailleCommande->setCommande($this);
        }

        return $this;
    }

    public function removeTailleCommande(TailleCommande $tailleCommande): self
    {
        if ($this->tailleCommandes->removeElement($tailleCommande)) {
            // set the owning side to null (unless already changed)
            if ($tailleCommande->getCommande() === $this) {
                $tailleCommande->setCommande(null);
            }
        }

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
