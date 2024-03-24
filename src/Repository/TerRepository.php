<?php

namespace App\Repository;
use App\Entity\Ter;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ter>
 *
 * @method Ter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ter[]    findAll()
 * @method Ter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ter::class);
    }

    public function save(Ter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Ter $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function search(string $looking = ''): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('c')
            ->from(Ter::class, 'c')
            ->where(
                $qb->expr()->orX(
                    $qb->expr()->like('c.nomSujet', ':looking'),
                )
            )
            ->setParameter('looking', '%' . $looking . '%');
        $query = $qb->getQuery();

        return $query->execute();

    }

    public function searchProf(Utilisateur $looking): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('c')
            ->from(Ter::class, 'c')
            ->where('c.createur = :looking')
            ->setParameter('looking', $looking);
        $query = $qb->getQuery();

        return $query->execute();

    }
//    public function searchProf(string $looking=''):array
//    {
//        $qb = $this->createQueryBuilder('t');
//        $qb ->select('t')
//            ->from(Ter::class, 't')
//            ->join('t.createur', 'c')
//            ->where('c.id = :user_id')
//            ->setParameter('user_id', $looking);
//
//
//        $query = $qb->getQuery();
//         return $query->execute();
//
//    }
    public function searchEtu(string $looking = ''): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('c')
            ->from(Ter::class, 'c')
            ->where('c.etudiant = :looking')
            ->setParameter('looking', '%' . $looking . '%');
        $query = $qb->getQuery();

        return $query->execute();

    }

    public function searchid(int $looking = 0): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('c')
            ->from(Ter::class, 'c')
            ->where('c.id = :looking')
            ->setParameter('looking', $looking);
        $query = $qb->getQuery();

        return $query->execute();

    }

    public function getNextId(): int
    {
        $maxId = $this->createQueryBuilder('t')
            ->select('MAX(t.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return $maxId + 1;
    }
}
