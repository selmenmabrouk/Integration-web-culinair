<?php

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ActiviteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups ;

/**
 * @property ArrayCollection $likes
 * @ORM\Entity(repositoryClass=ActiviteRepository::class)
 */
class Activite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

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
    private $nom;

    /**
     * @Assert\NotBlank(message="Le type d'activite doit etre non vide")
     * @Assert\Type(type="alpha", message="Le type d'activite ne doit pas contenir des chiffres .")
     * @Assert\Length(
     *      max = 15,
     *      maxMessage=" Très long !"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $type_activite;

    /**
     * @Assert\NotBlank(message="Ecrivez quelques chose !")
     * @Assert\Length(
     *      min = 10,
     *      max = 1000,
     *      minMessage = "Description très courte ! ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=1000)
     */
    private $description;


    /**
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le nombre de participants doit etre positif.")
     * @Assert\Range(
     *      min = 1,
     *      max = 8000,
     *      notInRangeMessage = "le prix doit etre valid",
     *     )
     * @ORM\Column(type="integer")
     */
    private $temps;

    /**
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le prix doit etre positif.")
     * @Assert\Range(
     *      min = 1,
     *      max = 8000,
     *      notInRangeMessage = "le prix doit etre valid",
     *     )
     * @ORM\Column(type="integer")
     */
    private $prix_activite;

    /**
     * @Assert\NotBlank(message="Entrez quelques choses !")
     * @Assert\Positive(message="Le nombre de participants doit etre positif.")
     * @ORM\Column(type="integer")
     */
    private $nombre_participant;

    /**
     * @ORM\OneToOne(targetEntity=Transport::class, cascade={"persist", "remove"},orphanRemoval=true)
     * @ORM\JoinColumn(nullable=false)
     */

    private $TypeTransport;

    /**
     * @ORM\OneToMany(targetEntity=ActiviteLike::class, mappedBy="Activite")
     */
    private $likes;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $LikeCount;


    public function __construct()
    {
        $this->likes = new ArrayCollection();
        $this->Likes = new ArrayCollection();
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

    public function getTypeActivite(): ?string
    {
        return $this->type_activite;
    }

    public function setTypeActivite(string $type_activite): self
    {
        $this->type_activite = $type_activite;

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

    public function getTemps(): ?string
    {
        return $this->temps;
    }

    public function setTemps(string $temps): self
    {
        $this->temps = $temps;

        return $this;
    }

    public function getPrixActivite(): ?float
    {
        return $this->prix_activite;
    }

    public function setPrixActivite(float $prix_activite): self
    {
        $this->prix_activite = $prix_activite;

        return $this;
    }

    public function getNombreParticipant(): ?int
    {
        return $this->nombre_participant;
    }

    public function setNombreParticipant(int $nombre_participant): self
    {
        $this->nombre_participant = $nombre_participant;

        return $this;
    }

    public function getTypeTransport(): ?Transport
    {
        return $this->TypeTransport;
    }

    public function setTypeTransport(Transport $TypeTransport): self
    {
        $this->TypeTransport = $TypeTransport;

        return $this;

    }

    /**
     * @return Collection<int, ActiviteLike>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(ActiviteLike $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
            $like->setActivite($this);
        }

        return $this;
    }

    public function removeLike(ActiviteLike $like): self
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getActivite() === $this) {
                $like->setActivite(null);
            }
        }

        return $this;
    }
    /**
     * @param User $user
     * @return boolean
     */
    public function isLikedByUser(User $user) : bool
    {
        foreach($this->likes as $like){
            if ($like->getUser() === $user) return true;
        }
        return false;
    }


    public function getLikeCount(): ?int
    {
        return $this->LikeCount;
    }

    public function setLikeCount(?int $LikeCount): self
    {
        $this->LikeCount = $LikeCount;

        return $this;
    }

}
