<?php

namespace App\Factory;

use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;


/**
 * @extends ModelFactory<Utilisateur>
 *
 * @method        Utilisateur|Proxy create(array|callable $attributes = [])
 * @method static Utilisateur|Proxy createOne(array $attributes = [])
 * @method static Utilisateur|Proxy find(object|array|mixed $criteria)
 * @method static Utilisateur|Proxy findOrCreate(array $attributes)
 * @method static Utilisateur|Proxy first(string $sortedField = 'id')
 * @method static Utilisateur|Proxy last(string $sortedField = 'id')
 * @method static Utilisateur|Proxy random(array $attributes = [])
 * @method static Utilisateur|Proxy randomOrCreate(array $attributes = [])
 * @method static UtilisateurRepository|RepositoryProxy repository()
 * @method static Utilisateur[]|Proxy[] all()
 * @method static Utilisateur[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Utilisateur[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Utilisateur[]|Proxy[] findBy(array $attributes)
 * @method static Utilisateur[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static Utilisateur[]|Proxy[] randomSet(int $number, array $attributes = [])
 */
final class UtilisateurFactory extends ModelFactory
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        parent::__construct();

        $this->passwordHasher = $passwordHasher;
    }

    protected function getDefaults(): array
    {
        $lst_roles = array(['ROLE_ETUDIANT'],['ROLE_PROFESSEUR'],['ROLE_ENTREPRISE']);
        $roles = $lst_roles[random_int(0, 2)];
        $adresse = self::faker()->buildingNumber() . ' ' . self::faker()->streetName();
        $code_postal = self::faker()->postcode();
        $code_postal = str_replace(' ', '', $code_postal);
        $ville = self::faker()->city();
        $password = 'test';
        $nom = self::faker()->lastName();
        $telephone = self::faker()->phoneNumber();
        $value = self::faker()->numerify();
        $sexe = null;
        $prenom = null;
        $classe = [];
        $ter = null;
        $stageAlt = null;
        $email = $prenom . '.' . $nom . '@';
        $cours = [];
        $cv = null;
        $ters = null;


        if (in_array('ROLE_ETUDIANT', $roles)) {
            $prenom = self::faker()->firstName();
            $email = $prenom . '.' . $nom . '-' . $value . '@';
            $email = mb_strtolower($email);
            $email = transliterator_transliterate('Any-Latin; Latin-ASCII', $email);
            $email = $email . self::faker()->domainName();
            $sexe = self::faker()->randomElement(['H', 'F']);
            $classe[] = ClasseFactory::random();
            $classe2 = ClasseFactory::random();
            $ters = [];

            while (in_array($classe2, $classe)) {
                $classe2 = ClasseFactory::random();
            }
            $classe[] = $classe2;
            for ($i = 0; $i < 20; $i++) {
                $cours[] = CalendrierFactory::random();
            }
//            for ($i = 0; $i < 2; $i++) {
//                $ters[] = TerFactory::random();
//            }
        }
        if (in_array('ROLE_PROFESSEUR', $roles)) {
            $prenom = self::faker()->firstName();
            $email = $prenom . '.' . $nom . '-' . $value . '@';
            $email = mb_strtolower($email);
            $email = transliterator_transliterate('Any-Latin; Latin-ASCII', $email);
            $email = $email . self::faker()->domainName();
            $sexe = self::faker()->randomElement(['H', 'F']);
            for ($i = 0; $i < 50; $i++) {
                $cours[] = CalendrierFactory::random();
            }
        }
        if (in_array('ROLE_ENTREPRISE', $roles)) {
            $lst_entreprise = array('Société', 'Entreprise', 'Corporation', 'Industrie');
            $type = $lst_entreprise[random_int(0, 3)];
            $nom = $type . '-' . $nom;
            $email = $nom . '-' . $value . '@';
            $email = mb_strtolower($email);
            $email = transliterator_transliterate('Any-Latin; Latin-ASCII', $email);
            $email = $email . self::faker()->domainName();
        }
        return ['adresse' => $adresse, 'email' => $email, 'code_postal' => $code_postal, 'nom' => $nom, 'password' => $password, 'prenom' => $prenom,
            'roles' => $roles, 'ville' => $ville, 'sexe' => $sexe, 'telephone' => $telephone, 'classes' => $classe, 'ter' => $ter,
            'stageAlt' => $stageAlt, 'cours' => $cours, 'cv' => $cv];
    }


    protected function initialize(): self
    {
        return $this
            ->afterInstantiate(function (Utilisateur $user) {
                $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPassword()));
            });
    }

    protected static function getClass(): string
    {
        return Utilisateur::class;
    }
}