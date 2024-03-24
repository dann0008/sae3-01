<?php

namespace App\Factory;

use App\Entity\StageAlt;
use App\Repository\StageAltRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<StageAlt>
 *
 * @method        StageAlt|Proxy create(array|callable $attributes = [])
 * @method static StageAlt|Proxy createOne(array $attributes = [])
 * @method static StageAlt|Proxy find(object|array|mixed $criteria)
 * @method static StageAlt|Proxy findOrCreate(array $attributes)
 * @method static StageAlt|Proxy first(string $sortedField = 'id')
 * @method static StageAlt|Proxy last(string $sortedField = 'id')
 * @method static StageAlt|Proxy random(array $attributes = [])
 * @method static StageAlt|Proxy randomOrCreate(array $attributes = [])
 * @method static StageAltRepository|RepositoryProxy repository()
 * @method static StageAlt[]|Proxy[] all()
 * @method static StageAlt[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static StageAlt[]|Proxy[] createSequence(array|callable $sequence)
 * @method static StageAlt[]|Proxy[] findBy(array $attributes)
 * @method static StageAlt[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static StageAlt[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class StageAltFactory extends ModelFactory
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
        $stages = array(
            "Développeur en IA",
            "Stage en Machine Learning",
            "Stage en Vision par ordinateur",
            "Stage en développement de jeux",
            "Stage en développement web",
            "Stage en cybersécurité",
            "Stage en développement mobile",
            "Stage en développement de logiciels",
            "Stage en développement de réseaux",
            "Stage en développement d'IA",
            "Stage en développement BD",
            "Stage en développement de cloud",
            "Stage en développement de blockchain",
            "Stage en développement de robots",
            "Stage en développement de VR/AR",
            "Stage en développement de chatbot",
            "Stage en développement de drones",
            "Stage en développement de IoT",
            "Stage en développement de QA",
            "développement de logiciel Embarqué",
            "développement de système d'exploitation",
            "développement de système de stockage",
            "développement de réalité augmentée",
            "développement de plateforme d'IA",
            "développement de solution de Big Data",
            "développement de solution de DevOps",
            "développement de système de gestion",
            "système de gestion de projet",
            "développement de solution de sécurité"
        );

        $utilisateur = UtilisateurFactory::random();
        $role = $utilisateur->getRoles();
        $dateDebut = self::faker()->dateTimeBetween('now','+1 year');
        $dateFin = self::faker()->dateTimeBetween('now','+1 year');
        while ($dateFin < $dateDebut) {
            $dateFin = self::faker()->dateTimeBetween('now','+1 year');
        }

        $liste_candidat = [];
        $etudiant_retenu = null;
        while (count($liste_candidat) !== 3) {
            if ($role[0] =='ROLE_ETUDIANT'){
                $liste_candidat[] = $utilisateur;
            } else {
                $utilisateur = UtilisateurFactory::random();
                $role = $utilisateur->getRoles();
            }
    }

        while ($role[0] !='ROLE_ENTREPRISE') {
            $utilisateur = UtilisateurFactory::random();
            $role = $utilisateur->getRoles();
        }

        return [
            'description' => self::faker()->text(),
            'nom' => $stages[self::faker()->numberBetween(0,27)],
            'entreprise' => $utilisateur,
            'type' => self::faker()->randomElement(['Alternance', 'Stage']),
            'candidats' => $liste_candidat,
            'etudiant_retenu' => $etudiant_retenu,
            'dateDebut' => $dateDebut,
            'dateFin' =>$dateFin,
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(StageAlt $stageAlt): void {})
        ;
    }

    protected static function getClass(): string
    {
        return StageAlt::class;
    }
}
