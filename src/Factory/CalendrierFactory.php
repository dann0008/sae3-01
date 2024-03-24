<?php

namespace App\Factory;

use App\Entity\Calendrier;
use App\Repository\CalendrierRepository;
use DateTime;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Calendrier>
 *
 * @method        Calendrier|Proxy create(array|callable $attributes = [])
 * @method static Calendrier|Proxy createOne(array $attributes = [])
 * @method static Calendrier|Proxy find(object|array|mixed $criteria)
 * @method static Calendrier|Proxy findOrCreate(array $attributes)
 * @method static Calendrier|Proxy first(string $sortedField = 'id')
 * @method static Calendrier|Proxy last(string $sortedField = 'id')
 * @method static Calendrier|Proxy random(array $attributes = [])
 * @method static Calendrier|Proxy randomOrCreate(array $attributes = [])
 * @method static CalendrierRepository|RepositoryProxy repository()
 * @method static Calendrier[]|Proxy[] all()
 * @method static Calendrier[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Calendrier[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Calendrier[]|Proxy[] findBy(array $attributes)
 * @method static Calendrier[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Calendrier[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class CalendrierFactory extends ModelFactory
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
            $couleur = self::faker()->safeHexColor();
            $jour = self::faker()->numberBetween(1,31);
            $mois = intval(date('n'));
            $annee = date('Y');
            while (checkdate($mois,$jour,intval($annee)) != true) {
                $jour = self::faker()->numberBetween(1,31);
                $mois = intval(date('n'));
            }
            $date = "$annee-$mois-$jour";
            $heure = self::faker()->numberBetween(8,17);
            $minutes = self::faker()->randomElement([0,15,30,45]);
            $heureFin = $heure + self::faker()->numberBetween(1,2);
            if ($minutes == 0) {
                $debut = DateTime::createFromFormat('Y-m-d H:i:s', $date." ".$heure.":00:00");
                $fin = DateTime::createFromFormat('Y-m-d H:i:s', $date." ".$heureFin.":00:00");
            } else {
                $debut = DateTime::createFromFormat('Y-m-d H:i:s', $date . " " . $heure . ":" . $minutes . ":00");
                $fin = DateTime::createFromFormat('Y-m-d H:i:s', $date." ".$heureFin. ":" . $minutes . ":00");
            }
            $description = self::faker()->text(1000);
            $matiere = [
                'Algorithmique',
                'Architecture, réseaux, systèmes',
                'Agents intelligents et multiagents',
                'Analyse d’image et vision par ordinateur',
                'Bases de données',
                'Conception et programmation parallèle et orientée objet',
                'Intelligence artificielle',
                'Ingénierie du logiciel : TER',
                'Management de projet',
                'Modélisation des composants logiciels',
                'Recherche opérationnelle',
                'Robotique',
                'Systèmes multimédias',
                'Sécurité des systèmes',
                'Spécification et traduction des langages',
                'Télécommunications'
            ];
            $titre = $matiere[array_rand($matiere)];


            return ['titre' => $titre, 'debut' => $debut, 'fin' => $fin, 'description' => $description, 'couleur' => $couleur, 'all_day' => false,];

    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Calendrier $calendrier): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Calendrier::class;
    }
}
