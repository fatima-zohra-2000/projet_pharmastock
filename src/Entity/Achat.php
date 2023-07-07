<?php

namespace App\Entity;

use App\Repository\AchatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AchatRepository::class)]
class Achat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $num_achat = null;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?Fournisseur $fournisseur = null;

    #[ORM\Column]
    private ?float $TVA = null;

    #[ORM\Column]
    private ?float $mantant_TVA = null;

    #[ORM\Column]
    private ?float $total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'achat', targetEntity: TailleAchat::class)]
    private Collection $tailleAchats;

    #[ORM\ManyToOne(inversedBy: 'achats')]
    private ?User $user = null;

    public function __construct()
    {
        $this->tailleAchats = new ArrayCollection();
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumAchat(): ?int
    {
        return $this->num_achat;
    }

    public function setNumAchat(int $num_achat): self
    {
        $this->num_achat = $num_achat;

        return $this;
    }

    public function getFournisseur(): ?Fournisseur
    {
        return $this->fournisseur;
    }

    public function setFournisseur(?Fournisseur $fournisseur): self
    {
        $this->fournisseur = $fournisseur;

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

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): self
    {
        $this->total = $total;

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
     * @return Collection<int, TailleAchat>
     */
    public function getTailleAchats(): Collection
    {
        return $this->tailleAchats;
    }

    public function addTailleAchat(TailleAchat $tailleAchat): self
    {
        if (!$this->tailleAchats->contains($tailleAchat)) {
            $this->tailleAchats->add($tailleAchat);
            $tailleAchat->setAchat($this);
        }

        return $this;
    }

    public function removeTailleAchat(TailleAchat $tailleAchat): self
    {
        if ($this->tailleAchats->removeElement($tailleAchat)) {
            // set the owning side to null (unless already changed)
            if ($tailleAchat->getAchat() === $this) {
                $tailleAchat->setAchat(null);
            }
        }

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
