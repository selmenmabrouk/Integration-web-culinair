<?php

namespace App\Entity;

use App\Repository\RestaurantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RestaurantRepository::class)
 */
class Restaurant
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Type(type="alpha", message="Le nom ne doit pas contenir des chiffres .")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage=" Très long !"
     *
     *     )
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     */
    private $Type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Length(
     *      min = 30,
     *      max = 1000,
     *      minMessage = "Description très courte ! ",
     *      maxMessage = "doit etre <=1000" )
     */
    private $Description;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     */
    private $Emplacement;

    /**
     * @ORM\Column(type="string", length=255)
     *
     */
    private $Photo;

    /**
     * @ORM\ManyToMany(targetEntity=Plat::class, mappedBy="Restau")
     */
    private $plats;



    public function __construct()
    {
        $this->plats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->Type;
    }

    public function setType(string $Type): self
    {
        $this->Type = $Type;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->Emplacement;
    }

    public function setEmplacement(string $Emplacement): self
    {
        $this->Emplacement = $Emplacement;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->Photo;
    }

    public function setPhoto(string $Photo): self
    {
        $this->Photo = $Photo;

        return $this;
    }

    /**
     * @return Collection<int, Plat>
     */
    public function getPlats(): Collection
    {
        return $this->plats;
    }

    public function addPlat(Plat $plat): self
    {
        if (!$this->plats->contains($plat)) {
            $this->plats[] = $plat;
            $plat->addRestau($this);
        }

        return $this;
    }

    public function removePlat(Plat $plat): self
    {
        if ($this->plats->removeElement($plat)) {
            $plat->removeRestau($this);
        }

        return $this;
    }

    public function __toString() { return (string) $this->getNom(); }


}
