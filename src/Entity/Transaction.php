<?php

namespace App\Entity;

use App\Enum\ModePaiement;
use App\Enum\StatutPaiement;
use App\Repository\TransactionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reference_transaction = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private ?string $montant_transaction = null;

    #[ORM\Column]
    private ?\DateTime $date_de_paiement = null;

    #[ORM\Column(enumType: StatutPaiement::class)]
    private ?StatutPaiement $statut_paiement = null;

    #[ORM\Column(type: Types::SIMPLE_ARRAY, enumType: ModePaiement::class)]
    private array $moyen_paiement = [];

    #[ORM\Column(nullable: true)]
    private ?int $idCompteStripe = null;

    #[ORM\ManyToOne]
    private ?User $user = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Reservation $reservation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReferenceTransaction(): ?string
    {
        return $this->reference_transaction;
    }

    public function setReferenceTransaction(string $reference_transaction): static
    {
        $this->reference_transaction = $reference_transaction;

        return $this;
    }

    public function getMontantTransaction(): ?string
    {
        return $this->montant_transaction;
    }

    public function setMontantTransaction(string $montant_transaction): static
    {
        $this->montant_transaction = $montant_transaction;

        return $this;
    }

    public function getDateDePaiement(): ?\DateTime
    {
        return $this->date_de_paiement;
    }

    public function setDateDePaiement(\DateTime $date_de_paiement): static
    {
        $this->date_de_paiement = $date_de_paiement;

        return $this;
    }

    public function getStatutPaiement(): ?StatutPaiement
    {
        return $this->statut_paiement;
    }

    public function setStatutPaiement(StatutPaiement $statut_paiement): static
    {
        $this->statut_paiement = $statut_paiement;

        return $this;
    }

    /**
     * @return ModePaiement[]
     */
    public function getMoyenPaiement(): array
    {
        return $this->moyen_paiement;
    }

    public function setMoyenPaiement(array $moyen_paiement): static
    {
        $this->moyen_paiement = $moyen_paiement;

        return $this;
    }

    public function getIdCompteStripe(): ?int
    {
        return $this->idCompteStripe;
    }

    public function setIdCompteStripe(?int $idCompteStripe): static
    {
        $this->idCompteStripe = $idCompteStripe;

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

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): static
    {
        $this->reservation = $reservation;

        return $this;
    }
}
