<?php

namespace App\Entity;

use App\Repository\PromotionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PromotionRepository::class)
 */
class Promotion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 0,
     *      max = 100,
     *      notInRangeMessage = "Cette valeur doit etre entre {{ min }} et {{ max }}",
     * )
     */
    private $tauxPromotion;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotNull
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Evenement::class, mappedBy="Promotion",cascade={"all"} )
     */
    private $evenements;

    public function __construct()
    {
        $this->evenements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTauxPromotion(): ?int
    {
        return $this->tauxPromotion;
    }

    public function setTauxPromotion(int $tauxPromotion): self
    {
        $this->tauxPromotion = $tauxPromotion;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Evenement[]
     */
    public function getEvenements(): Collection
    {
        return $this->evenements;
    }

    public function addEvenement(Evenement $evenement): self
    {
        if (!$this->evenements->contains($evenement)) {
            $this->evenements[] = $evenement;
            $evenement->setPromotion($this);
        }

        return $this;
    }

    public function removeEvenement(Evenement $evenement): self
    {
        if ($this->evenements->removeElement($evenement)) {
            // set the owning side to null (unless already changed)
            if ($evenement->getPromotion() === $this) {
                $evenement->setPromotion(null);
            }
        }

        return $this;
    }
}
