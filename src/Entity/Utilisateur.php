<?php

namespace App\Entity;

use App\Repository\UtilisateurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[ORM\OneToOne(inversedBy: 'ProposeurdeStage')]
    protected ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    protected ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    protected ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 50)]
    private ?string $adresse = null;

    #[ORM\Column(length: 5)]
    private ?string $code_postal = null;

    #[ORM\Column(length: 50)]
    private ?string $ville = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 1, nullable: true)]
    private ?string $sexe = null;

    #[ORM\OneToMany(mappedBy: 'createur', targetEntity: Ter::class)]
    private Collection $ters;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: StageAlt::class)]
    private Collection $stageAlts;

    #[ORM\ManyToMany(targetEntity: StageAlt::class, mappedBy: 'candidats')]
    private Collection $candidatures;

    #[ORM\OneToOne(mappedBy: 'etudiant_retenu', cascade: ['persist', 'remove'])]
    private ?StageAlt $stageAlt = null;

    #[ORM\ManyToMany(targetEntity: Calendrier::class, inversedBy: 'utilisateurs')]
    private Collection $cours;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cv = null;

    #[ORM\OneToOne(mappedBy: 'etudiant', cascade: ['persist', 'remove'])]
    private ?Ter $ter = null;

    #[ORM\ManyToMany(targetEntity: Ter::class,mappedBy:'CandidatsInteresed')]
    private Collection $candidaturesTers;

    #[ORM\ManyToMany(targetEntity: Classe::class, inversedBy: 'utilisateurs')]
    private Collection $classes;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;


    public function __construct()
    {
        $this->ters = new ArrayCollection();
        $this->stageAlts = new ArrayCollection();
        $this->cours = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
        $this->candidaturesTers = new ArrayCollection();
        $this->classes = new ArrayCollection();
    }
    public function addCandidatureter(Ter $candidature): self
    {
        if (!$this->ters->contains($candidature)) {
            $this->ters->add($candidature);
            $candidature->addCandidatsInteresed($this);
        }

        return $this;
    }



    /**
     * @return Collection<int, Ter>
     */
    public function getCandidaturesTers(): Collection
    {
        return $this->candidaturesTers;
    }

    public function addCandidaturesTer(Ter $candidaturesTer): self
    {
        if (!$this->candidaturesTers->contains($candidaturesTer)) {
            $this->candidaturesTers->add($candidaturesTer);
        }

        return $this;
    }
    public function removeOneCandidaturesTer(Ter $ter)
    {
            $this->candidaturesTers->removeElement($ter);
            $ter->removeCandidatInteresed($this);

    }
    public function removeCandidaturesTer(Ter $ter)
    {

        if ($this->candidaturesTers->contains($ter)) {
            $this->candidaturesTers->removeElement($ter);
            // $ter->removeCandidatInteresed($this);
            $this->candidaturesTers->clear();
        }
    }
    public function removeOneCandidaturesStageAlt(StageAlt $stageAlt)
    {
        $this->candidatures->removeElement($stageAlt);
        $stageAlt->removeCandidatInteresed($this);

    }
    public   function removeCandidaturesStageAlt(StageAlt $stageAlt)
    {

        if ($this->candidatures->contains($stageAlt)) {
            $this->candidatures->removeElement($stageAlt);
            // $ter->removeCandidatInteresed($this);
            $this->candidatures->clear();
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * @return Collection<int, Ter>
     */
    public function getTers(): Collection
    {
        return $this->ters;
    }

    public function addTer(Ter $ter): self
    {
        if (!$this->ters->contains($ter)) {
            $this->ters->add($ter);
            $ter->setCreateur($this);
        }

        return $this;
    }

    public function removeTer(Ter $ter): self
    {
        if ($this->ters->removeElement($ter)) {
            // set the owning side to null (unless already changed)
            if ($ter->getCreateur() === $this) {
                $ter->setCreateur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StageAlt>
     */
    public function getStageAlts(): Collection
    {
        return $this->stageAlts;
    }

    public function addStageAlt(StageAlt $stageAlt): self
    {
        if (!$this->stageAlts->contains($stageAlt)) {
            $this->stageAlts->add($stageAlt);
            $stageAlt->setEntreprise($this);
        }

        return $this;
    }

    public function removeStageAlt(StageAlt $stageAlt): self
    {
        if ($this->stageAlts->removeElement($stageAlt)) {
            // set the owning side to null (unless already changed)
            if ($stageAlt->getEntreprise() === $this) {
                $stageAlt->setEntreprise(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, StageAlt>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(StageAlt $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures->add($candidature);
            $candidature->addCandidat($this);
        }

        return $this;
    }

    public function removeCandidature(StageAlt $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            $candidature->removeCandidat($this);
        }

        return $this;
    }

    public function getStageAlt(): ?StageAlt
    {
        return $this->stageAlt;
    }

    public function setStageAlt(?StageAlt $stageAlt): self
    {

        $this->stageAlt = $stageAlt;

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

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getTer(): ?Ter
    {
        return $this->ter;
    }

    public function setTer(?Ter $ter): self
    {

        $this->ter = $ter;

        return $this;
    }

    /**
     * @return Collection<int, Classe>
     */
    public function getClasses(): Collection
    {
        return $this->classes;
    }

    public function addClass(Classe $class): self
    {
        if (!$this->classes->contains($class)) {
            $this->classes->add($class);
        }

        return $this;
    }

    public function removeClass(Classe $class): self
    {
        $this->classes->removeElement($class);

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
