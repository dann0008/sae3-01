<?php

namespace App\Factory;

use App\Entity\Classe;
use App\Repository\ClasseRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Classe>
 *
 * @method        Classe|Proxy create(array|callable $attributes = [])
 * @method static Classe|Proxy createOne(array $attributes = [])
 * @method static Classe|Proxy find(object|array|mixed $criteria)
 * @method static Classe|Proxy findOrCreate(array $attributes)
 * @method static Classe|Proxy first(string $sortedField = 'id')
 * @method static Classe|Proxy last(string $sortedField = 'id')
 * @method static Classe|Proxy random(array $attributes = [])
 * @method static Classe|Proxy randomOrCreate(array $attributes = [])
 * @method static ClasseRepository|RepositoryProxy repository()
 * @method static Classe[]|Proxy[] all()
 * @method static Classe[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Classe[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Classe[]|Proxy[] findBy(array $attributes)
 * @method static Classe[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Classe[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class ClasseFactory extends ModelFactory
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
        $cours = [];
        for ($i = 0; $i < 50; $i++) {
            $cours[] = CalendrierFactory::random();
        }
        $nombre = self::faker()->numberBetween(1,2);
        $nom = 'M'.$nombre.'-'. self::faker()->unique()->word();
        if ($nombre == 1) {
            $niveau = 'M1';
        }
        else {
            $niveau = 'M2';
        }
        return ['nom' => $nom, 'niveau' => $niveau,'cours'=> $cours];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Classe $classe): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Classe::class;
    }
}
