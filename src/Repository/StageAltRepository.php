<?php

namespace App\Repository;

use App\Entity\StageAlt;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StageAlt>
 *
 * @method StageAlt|null find($id, $lockMode = null, $lockVersion = null)
 * @method StageAlt|null findOneBy(array $criteria, array $orderBy = null)
 * @method StageAlt[]    findAll()
 * @method StageAlt[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StageAltRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StageAlt::class);
    }

    public function save(StageAlt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(StageAlt $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchEnt(Utilisateur $looking): array
    {
        $qb = $this->createQueryBuilder('u');
        $qb->select('s')
            ->from(StageAlt::class, 's')
            ->where('s.entreprise = :looking')
            ->setParameter('looking', $looking);
        $query = $qb->getQuery();

        return $query->execute();
    }

    public function search($texts = ''): array
    {
        $qd = $this->createQueryBuilder('u');
        $qd->select('s')
            ->from(StageAlt::class, 's')

            ->where(
                $qd->expr()->like('s.nom', ':t')
            )

            ->setParameter('t', "%$texts%");

        $query = $qd->getQuery();

        return $query->execute();
    }

//    /**
//     * @return StageAlt[] Returns an array of StageAlt objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?StageAlt
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
