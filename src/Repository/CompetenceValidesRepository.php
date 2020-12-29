<?php

namespace App\Repository;

use App\Entity\CompetenceValides;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CompetenceValides|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetenceValides|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetenceValides[]    findAll()
 * @method CompetenceValides[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenceValidesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetenceValides::class);
    }

    // /**
    //  * @return CompetenceValides[] Returns an array of CompetenceValides objects
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
    public function findOneBySomeField($value): ?CompetenceValides
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
