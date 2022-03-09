<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=VoyageRepository::class)
 */
class Voyage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @var \Datetime
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "DATE INVALIDE"
     * )
     */
    private $dateDepart;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @var \Datetime
     * @Assert\Type(
     *      type = "\DateTime",
     *      message = "vacancy.date.valid",
     * )
     * @Assert\GreaterThanOrEqual(
     *      value = "today",
     *      message = "DATE INVALIDE"
     * )
     * @Assert\Expression(
     *     "this.getDateRetour() >= this.getDateDepart()",
     *     message="La Date de retour doit etre superieur a la date de depart"
     * )
     *
     */
    private $dateRetour;

    /**
     * @ORM\Column(type="integer")
    @Assert\Range(
     *      min = 1,
     *      max = 10,
     *      notInRangeMessage = "Le nombre doit etre entre {{ min }} voyageur et {{ max }} voyageurs ",
     * )
     */
    private $nbrVoyageurs;

    /**
     * @ORM\Column(type="float")
     *@Assert\Positive(message="Merci de renseigner un budget positive")
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Ville::class, inversedBy="voyages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ville;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDepart(): ?\DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(\DateTimeInterface $dateDepart): self
    {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): self
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    public function getNbrVoyageurs(): ?int
    {
        return $this->nbrVoyageurs;
    }

    public function setNbrVoyageurs(int $nbrVoyageurs): self
    {
        $this->nbrVoyageurs = $nbrVoyageurs;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

}
