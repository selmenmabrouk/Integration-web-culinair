<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $Num_vol;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("post:read")
     */
    private $Destination;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $Date_depart;

    /**
     * @ORM\Column(type="date")
     * @Groups("post:read")
     */
    private $Date_arrivee;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $Adulte;

    /**
     * @ORM\Column(type="integer")
     * @Groups("post:read")
     */
    private $Enfant;

    /**
     * @ORM\ManyToOne(targetEntity=Evenement::class, inversedBy="yes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Event;

    /**
     * @ORM\ManyToOne(targetEntity=Guide::class, inversedBy="yes")
     */
    private $Guide;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $paid = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumVol(): ?int
    {
        return $this->Num_vol;
    }

    public function setNumVol(int $Num_vol): self
    {
        $this->Num_vol = $Num_vol;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->Destination;
    }

    public function setDestination(string $Destination): self
    {
        $this->Destination = $Destination;

        return $this;
    }

    public function getDateDepart(): ?DateTimeInterface
    {
        return $this->Date_depart;
    }

    public function setDateDepart(DateTimeInterface $Date_depart): self
    {
        $this->Date_depart = $Date_depart;

        return $this;
    }

    public function getDateArrivee(): ?DateTimeInterface
    {
        return $this->Date_arrivee;
    }

    public function setDateArrivee(DateTimeInterface $Date_arrivee): self
    {
        $this->Date_arrivee = $Date_arrivee;

        return $this;
    }

    public function getAdulte(): ?int
    {
        return $this->Adulte;
    }

    public function setAdulte(int $Adulte): self
    {
        $this->Adulte = $Adulte;

        return $this;
    }

    public function getEnfant(): ?int
    {
        return $this->Enfant;
    }

    public function setEnfant(int $Enfant): self
    {
        $this->Enfant = $Enfant;

        return $this;
    }

    public function getEvent(): ?Evenement
    {
        return $this->Event;
    }

    public function setEvent(?Evenement $Event): self
    {
        $this->Event = $Event;

        return $this;
    }

    public function getGuide(): ?Guide
    {
        return $this->Guide;
    }

    public function setGuide(?Guide $Guide): self
    {
        $this->Guide = $Guide;

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

    public function getPaid(): ?bool
    {
        return $this->paid;
    }

    public function setPaid(bool $paid): self
    {
        $this->paid = $paid;

        return $this;
    }
}
