<?php

namespace App\Entity;

use App\Repository\EvenementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EvenementRepository::class)
 */
class Evenement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */

    private $nom;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Range (
     * min = "now",
     * notInRangeMessage = "Date non valide",)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Promotion::class, inversedBy="evenements")
     */
    private $Promotion;

    /**
     * @ORM\Column(type="integer")
     */
    private $Prix;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="Event", orphanRemoval=true)
     */
    private $yes;

    public function __construct()
    {
        $this->yes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->Promotion;
    }

    public function setPromotion(?Promotion $Promotion): self
    {
        $this->Promotion = $Promotion;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->Prix;
    }

    public function setPrix(int $Prix): self
    {
        $this->Prix = $Prix;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getYes(): Collection
    {
        return $this->yes;
    }

    public function addYe(Reservation $ye): self
    {
        if (!$this->yes->contains($ye)) {
            $this->yes[] = $ye;
            $ye->setEvent($this);
        }

        return $this;
    }

    public function removeYe(Reservation $ye): self
    {
        if ($this->yes->removeElement($ye)) {
            // set the owning side to null (unless already changed)
            if ($ye->getEvent() === $this) {
                $ye->setEvent(null);
            }
        }

        return $this;
    }
}
