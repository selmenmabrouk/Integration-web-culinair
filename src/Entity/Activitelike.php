<?php

namespace App\Entity;

use App\Repository\ActivitelikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivitelikeRepository::class)
 */
class Activitelike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Activite::class, inversedBy="Likes")
     */
    private $Activitelike;

    /**
     * @ORM\ManyToOne(targetEntity=Transport::class, inversedBy="Likes")
     */
    private $Transportlike;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivitelike(): ?Activite
    {
        return $this->Activitelike;
    }

    public function setActivitelike(?Activite $Activitelike): self
    {
        $this->Activitelike = $Activitelike;

        return $this;
    }

    public function getTransportlike(): ?Transport
    {
        return $this->Transportlike;
    }

    public function setTransportlike(?Transport $Transportlike): self
    {
        $this->Transportlike = $Transportlike;

        return $this;
    }
}
