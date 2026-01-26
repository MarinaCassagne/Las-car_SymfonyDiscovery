<?php

namespace App\Entity;

use App\Enum\CanalDeNotification;
use App\Enum\StatutNotification;
use App\Repository\NotificationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationRepository::class)]
class Notification
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTime $date_notification = null;

    #[ORM\Column(enumType: CanalDeNotification::class)]
    private ?CanalDeNotification $canal_de_notification = null;

    #[ORM\Column(enumType: StatutNotification::class)]
    private ?StatutNotification $statut_notification = null;

    #[ORM\ManyToOne(inversedBy: 'idNotification')]
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

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateNotification(): ?\DateTime
    {
        return $this->date_notification;
    }

    public function setDateNotification(\DateTime $date_notification): static
    {
        $this->date_notification = $date_notification;

        return $this;
    }

    public function getCanalDeNotification(): ?CanalDeNotification
    {
        return $this->canal_de_notification;
    }

    public function setCanalDeNotification(CanalDeNotification $canal_de_notification): static
    {
        $this->canal_de_notification = $canal_de_notification;

        return $this;
    }

    public function getStatutNotification(): ?StatutNotification
    {
        return $this->statut_notification;
    }

    public function setStatutNotification(StatutNotification $statut_notification): static
    {
        $this->statut_notification = $statut_notification;

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
