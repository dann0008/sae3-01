<?php

namespace App\Entity;

use App\Repository\StageAltRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

#[ORM\Entity(repositoryClass: StageAltRepository::class)]
class StageAlt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'stageAlts')]
    private ?Utilisateur $entreprise = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\ManyToMany(targetEntity: Utilisateur::class, inversedBy: 'candidatures')]
    private Collection $candidats;

    #[ORM\OneToOne(inversedBy: 'stageAlt', cascade: ['persist', 'remove'])]
    private ?Utilisateur $etudiant_retenu = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    public function __construct()
    {
        $this->candidats = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEntreprise(): ?Utilisateur
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Utilisateur $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Utilisateur>
     */
    public function getCandidats(): Collection
    {
        return $this->candidats;
    }

    public function addCandidat(Utilisateur $candidat): self
    {
        if (!$this->candidats->contains($candidat)) {
            $this->candidats->add($candidat);
        }

        return $this;
    }

    public function removeCandidat(Utilisateur $candidat): self
    {
        $this->candidats->removeElement($candidat);

        return $this;
    }
    public function clearCandidats(): void
    {
        $this->candidats->clear();
    }

    public function getEtudiantRetenu(): ?Utilisateur
    {
        return $this->etudiant_retenu;
    }

    public function setEtudiantRetenu(?Utilisateur $utilisateur): self
    {
        $this->etudiant_retenu = $utilisateur;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }
    public function getEtudiant_retenu(){
        return $this->etudiant_retenu;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }public function removeCandidatInteresed(Utilisateur $candidatsInteresed): self
{
    $this->candidats->removeElement($candidatsInteresed);

    return $this;
}

}
