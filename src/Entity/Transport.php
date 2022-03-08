<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\TransportRepository;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=TransportRepository::class)
 */
class Transport
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le nombre de participants doit etre positif.")
     * @ORM\Column(type="integer")
     */
    private $duree_transport;
    /**
     * @Assert\NotBlank(message="Le nom doit etre non vide")
     * @Assert\Type(type="alpha", message="Le nom ne doit pas contenir des chiffres .")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage=" Très long !"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $type_transport;

    /**
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le nombre de participants doit etre positif.")
     * @Assert\Range(
     *      min = 1,
     *      max = 1500,
     *      notInRangeMessage = "la capacité doit etre valid",
     *     )
     * @ORM\Column(type="integer")
     */
    private $capacite;

    /**
     * @ORM\OneToMany(targetEntity=ActiviteLike::class, mappedBy="Transportlike")
     */
    private $likes;

    /**
     * @ORM\OneToMany(targetEntity=ActiviteLike::class, mappedBy="Transportlike")
     */
    private $Likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->Likes = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDureeTransport(): ?int
    {
        return $this->duree_transport;
    }

    public function setDureeTransport(int $duree_transport): self
    {
        $this->duree_transport = $duree_transport;

        return $this;
    }

    public function getTypeTransport(): ?string
    {
        return $this->type_transport;
    }

    public function setTypeTransport(string $type_transport): self
    {
        $this->type_transport = $type_transport;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }
    public function __toString() { return (string) $this->getTypeTransport(); }

    /**
     * @return Collection|ActiviteLike[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ActiviteLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setTransportlike($this);
        }

        return $this;
    }

    public function removeLike(ActiviteLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getTransportlike() === $this) {
                $like->setTransportlike(null);
            }
        }

        return $this;
    }


}
