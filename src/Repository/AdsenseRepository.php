<?php

namespace App\Repository;

use App\Entity\Adsense;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Adsense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adsense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adsense[]    findAll()
 * @method Adsense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adsense::class);
    }

    // /**
    //  * @return Adsense[] Returns an array of Adsense objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Adsense
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
