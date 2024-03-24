<?php

namespace App\Entity;

use App\Repository\ClasseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClasseRepository::class)]
class Classe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $nom = null;

    #[ORM\Column(length: 20)]
    private ?string $niveau = null;

    #[ORM\ManyToMany(targetEntity: Calendrier::class, inversedBy: 'classes')]
    private Collection $cours;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, mappedBy: 'classes')]
    private Collection $utilisateurs;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->utilisateurs = new ArrayCollection();
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

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): self
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Utilisateur $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setClasse($this);
        }

        return $this;
    }

    public function removeEtudiant(Utilisateur $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getClasse() === $this) {
                $etudiant->setClasse(null);
            }
        }

        return $this;
    }


/**
 * @return Collection<int, Calendrier>
 */
public function getCours(): Collection
{
    return $this->cours;
}

public function addCour(Calendrier $cour): self
{
    if (!$this->cours->contains($cour)) {
        $this->cours->add($cour);
    }

    return $this;
}

public function removeCour(Calendrier $cour): self
{
    $this->cours->removeElement($cour);

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
        $utilisateur->addClass($this);
    }

    return $this;
}

public function removeUtilisateur(Utilisateur $utilisateur): self
{
    if ($this->utilisateurs->removeElement($utilisateur)) {
        $utilisateur->removeClass($this);
    }

    return $this;
}

public function __toString() {
    return $this->nom;
}
}
