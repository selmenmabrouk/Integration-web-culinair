<?php

namespace App\Entity;

use App\Repository\ActiviteLikeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActiviteLikeRepository::class)
 */
class ActiviteLike
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Activite::class, inversedBy="likes")
     */
    private $Activite;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="likes")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivite(): ?Activite
    {
        return $this->Activite;
    }

    public function setActivite(?Activite $Activite): self
    {
        $this->Activite = $Activite;

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
}
