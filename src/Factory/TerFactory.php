<?php

namespace App\Factory;

use App\Entity\Ter;
use App\Repository\TerRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Ter>
 *
 * @method        Ter|Proxy create(array|callable $attributes = [])
 * @method static Ter|Proxy createOne(array $attributes = [])
 * @method static Ter|Proxy find(object|array|mixed $criteria)
 * @method static Ter|Proxy findOrCreate(array $attributes)
 * @method static Ter|Proxy first(string $sortedField = 'id')
 * @method static Ter|Proxy last(string $sortedField = 'id')
 * @method static Ter|Proxy random(array $attributes = [])
 * @method static Ter|Proxy randomOrCreate(array $attributes = [])
 * @method static TerRepository|RepositoryProxy repository()
 * @method static Ter[]|Proxy[] all()
 * @method static Ter[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Ter[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Ter[]|Proxy[] findBy(array $attributes)
 * @method static Ter[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Ter[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class TerFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        $sujetTer = array(
            "Compression d’ensembles ordonnés",
            "Outils logiciels pour la gestion et la visualisation de données de capteurs",
            "Création de 2 applications web – CMS et Framework",
            "Création d’une interface frontend - bot de trading customisable",
            "Refonte de l’architecture d’un site Web",
            "Développement d’un gestionnaire de cycles de vie de services pour l’IoT",
            "Développement d’un générateur de flow Node-Red pour l’IoT",
            "Neuromorphic hardware comparison: Human Brain Project SpiNNaker vs Intel Loihi",
            "Stereo Matching for Event-Based Cameras Demonstration",
            "Synaptic delays for temporal pattern recognition",
            "Ontologie pour décrire les types de publications scientifiques",
            "Outil pour exploration d’une base de connaissance musicale",
            "Cross compilation C++/Rust/Web assembly, écriture d’un logiciel hôte pour des plugins WebAudio écrits en WebAssembly",
            "Ecriture d’une application “multi-effets audio à base de plugins de type WebAudio Modules 2.0”",
            "Étude des compteurs matériels des performances",
            "Comparaison des performances de codes de calculs scientifiques C++ versus Java",
            "Métriques de variabilité : des calculs à la visualisation",
            "Human motion capture using RGBD cameras",
            "Création d’une application pour la visualisation en 2D de la similarité entre structures chimiques",
            "Automatic Identification of variability implementations in JavaScript",
            "Visualisation de graphes sur un tore",
            "Les femmes en politique victimes de misogynie? Twitter comme objet d’étude de ce phénomène",
            "Quel climat quant à la communication des politiques face à la pandémie: étude des flux Twitter lors des allocutions présidentielles",
            "An investigation of the effect of the camera parameters on the generalization of deep learning for computer vision",
            "comparison of L* and NL* algorithm"
        );
        $nomsujet = $sujetTer[self::faker()->unique()->numberBetween(0,24)];
        $descr=self::faker()->text(500);
        $etudiant = null;

        $utilisateur = UtilisateurFactory::random();
        $role = $utilisateur->getRoles();
        while ($role[0] !='ROLE_PROFESSEUR') {
            $utilisateur = UtilisateurFactory::random();
            $role = $utilisateur->getRoles();
        }

        return [
            'description' =>$descr,
            'nomSujet' => $nomsujet,
            'createur' => $utilisateur,
            'etudiant' => $etudiant,

        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Ter $ter): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Ter::class;
    }
}
