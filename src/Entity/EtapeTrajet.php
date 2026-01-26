<?php

namespace App\Entity;

use App\Repository\EtapeTrajetRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtapeTrajetRepository::class)]
class EtapeTrajet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $latitude_etape = null;

    #[ORM\Column]
    private ?float $longitude_etape = null;

    #[ORM\ManyToOne(inversedBy: 'idEtapeTrajet')]
    private ?Trajet $Trajet = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitudeEtape(): ?float
    {
        return $this->latitude_etape;
    }

    public function setLatitudeEtape(float $latitude_etape): static
    {
        $this->latitude_etape = $latitude_etape;

        return $this;
    }

    public function getLongitudeEtape(): ?float
    {
        return $this->longitude_etape;
    }

    public function setLongitudeEtape(float $longitude_etape): static
    {
        $this->longitude_etape = $longitude_etape;

        return $this;
    }

    public function getTrajet(): ?Trajet
    {
        return $this->Trajet;
    }

    public function setTrajet(?Trajet $Trajet): static
    {
        $this->Trajet = $Trajet;

        return $this;
    }
}
