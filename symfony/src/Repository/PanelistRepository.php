<?php

namespace App\Repository;

use App\Entity\Panelist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Panelist>
 *
 * @method Panelist|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panelist|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panelist[]    findAll()
 * @method Panelist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanelistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Panelist::class);
    }

    //    /**
    //     * @return Panelist[] Returns an array of Panelist objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Panelist
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
