<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $note = null;

    #[ORM\Column]
    private ?\DateTime $date_avis = null;

    #[ORM\OneToOne(mappedBy: 'Avis', cascade: ['persist', 'remove'])]
    private ?Moderation $idModeration = null;

    #[ORM\ManyToOne(inversedBy: 'idAvis')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getDateAvis(): ?\DateTime
    {
        return $this->date_avis;
    }

    public function setDateAvis(\DateTime $date_avis): static
    {
        $this->date_avis = $date_avis;

        return $this;
    }

    public function getIdModeration(): ?Moderation
    {
        return $this->idModeration;
    }

    public function setIdModeration(?Moderation $idModeration): static
    {
        // unset the owning side of the relation if necessary
        if ($idModeration === null && $this->idModeration !== null) {
            $this->idModeration->setAvis(null);
        }

        // set the owning side of the relation if necessary
        if ($idModeration !== null && $idModeration->getAvis() !== $this) {
            $idModeration->setAvis($this);
        }

        $this->idModeration = $idModeration;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
