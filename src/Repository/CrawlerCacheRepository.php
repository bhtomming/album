<?php

namespace App\Repository;

use App\Entity\CrawlerCache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CrawlerCache|null find($id, $lockMode = null, $lockVersion = null)
 * @method CrawlerCache|null findOneBy(array $criteria, array $orderBy = null)
 * @method CrawlerCache[]    findAll()
 * @method CrawlerCache[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CrawlerCacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CrawlerCache::class);
    }



    // /**
    //  * @return CrawlerCache[] Returns an array of CrawlerCache objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CrawlerCache
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
