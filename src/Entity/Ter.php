<?php

namespace App\Entity;

use App\Repository\TerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TerRepository::class)]
class Ter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 200)]
    private ?string $nomSujet = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'ters')]
    private ?Utilisateur $createur = null;

    #[ORM\OneToOne(inversedBy: 'ter', cascade: ['persist', 'remove'])]
    private ?Utilisateur $etudiant = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy:'candidaturesTers')]
    private Collection $CandidatsInteresed;

    public function __construct()
    {
        $this->CandidatsInteresed = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdter(): ?int
    {
        return $this->idter;
    }

    public function setIdter(int $idter): self
    {
        $this->idter = $idter;

        return $this;
    }

    public function getNomSujet(): ?string
    {
        return $this->nomSujet;
    }

    public function setNomSujet(string $nomSujet): self
    {
        $this->nomSujet = $nomSujet;

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

    public function getCreateur(): ?Utilisateur
    {
        return $this->createur;
    }

    public function setCreateur(?Utilisateur $createur): self
    {
        $this->createur = $createur;

        return $this;
    }

    public function getEtudiant(): ?Utilisateur
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Utilisateur $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getCandidatsInteresed(): Collection
    {
        return $this->CandidatsInteresed;
    }

    public function addCandidatsInteresed(Utilisateur $candidatsInteresed): self
    {
        if (!$this->CandidatsInteresed->contains($candidatsInteresed)) {
            $this->CandidatsInteresed->add($candidatsInteresed);
        }

        return $this;
    }

    public function removeCandidatInteresed(Utilisateur $candidatsInteresed): self
    {
        $this->CandidatsInteresed->removeElement($candidatsInteresed);

        return $this;
    }
    public function clearDemandes (): self {
        $this->CandidatsInteresed->clear();
        return $this;
    }



}
