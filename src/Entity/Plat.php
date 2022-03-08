<?php

namespace App\Entity;

use App\Repository\PlatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PlatRepository::class)
 */
class Plat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("post:read")
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
     * @Groups("post:read")
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Groups("post:read")
     */
    private $Type_cuisine;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Length(
     *      min = 30,
     *      max = 1000,
     *      minMessage = "Description très courte ! ",
     *      maxMessage = "doit etre <=1000" )
     * @Groups("post:read")
     */
    private $Description;

    /**
     * @ORM\Column(type="integer", length=100)
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le prix doit etre positif.")
     * @Groups("post:read")
     */

    private $Prix;



    /**
     * @ORM\Column(type="string", length=255)
     *
     *
     */
    private $Photo;

    /**
     * @ORM\ManyToMany(targetEntity=Restaurant::class, inversedBy="plats")
     */
    private $Restau;

    public function __construct()
    {
        $this->Restau = new ArrayCollection();
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

    public function getTypeCuisine(): ?string
    {
        return $this->Type_cuisine;
    }

    public function setTypeCuisine(string $Type_cuisine): self
    {
        $this->Type_cuisine = $Type_cuisine;

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

    public function getPrix(): ?float
    {
        return $this->Prix;
    }

    public function setPrix(float $Prix): self
    {
        $this->Prix = $Prix;

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
     * @return Collection<int, Restaurant>
     */
    public function getRestau(): Collection
    {
        return $this->Restau;
    }

    public function addRestau(Restaurant $restau): self
    {
        if (!$this->Restau->contains($restau)) {
            $this->Restau[] = $restau;
        }

        return $this;
    }

    public function removeRestau(Restaurant $restau): self
    {
        $this->Restau->removeElement($restau);

        return $this;
    }

    public function __toString() { return (string) $this->getRestau(); }
}
