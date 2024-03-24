<?php

namespace App\Entity;

use App\Repository\CalendrierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CalendrierRepository::class)]
class Calendrier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $fin = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $all_day = null;

    #[ORM\Column(length: 7)]
    private ?string $couleur = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'cours')]
    private Collection $utilisateurs;

    #[ORM\ManyToMany(targetEntity: Classe::class, mappedBy: 'cours')]
    private Collection $classes;


//    #[ORM\ManyToMany(targetEntity: Classe::class, mappedBy: 'cours')]
//    private Collection $classes;

    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->professeurs = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDebut(): ?\DateTimeInterface
    {
        return $this->debut;
    }

    public function setDebut(\DateTimeInterface $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeInterface
    {
        return $this->fin;
    }

    public function setFin(\DateTimeInterface $fin): self
    {
        $this->fin = $fin;

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

    public function isAllDay(): ?bool
    {
        return $this->all_day;
    }

    public function setAllDay(bool $all_day): self
    {
        $this->all_day = $all_day;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

        return $this;
    }


/**
 * @return Collection<int, Utilisateur>
 */
public function getUtilisateurs(): Collection
{
    return $this->utilisateurs;
}

public function addUtilisateur(Utilisateur $utilisateur): self
{
    if (!$this->utilisateurs->contains($utilisateur)) {
        $this->utilisateurs->add($utilisateur);
        $utilisateur->addCour($this);
    }

    return $this;
}

public function removeUtilisateur(Utilisateur $utilisateur): self
{
    if ($this->utilisateurs->removeElement($utilisateur)) {
        $utilisateur->removeCour($this);
    }

    return $this;
}

/**
 * @return Collection<int, Classe>
 */
public function getClasses(): Collection
{
    return $this->classes;
}

public function removeAllClasses(): self
{
    $this->classes = new ArrayCollection();

    return $this;
}

}
