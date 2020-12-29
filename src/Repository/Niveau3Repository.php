<?php

namespace App\Repository;

use App\Entity\Niveau3;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Niveau3|null find($id, $lockMode = null, $lockVersion = null)
 * @method Niveau3|null findOneBy(array $criteria, array $orderBy = null)
 * @method Niveau3[]    findAll()
 * @method Niveau3[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class Niveau3Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Niveau3::class);
    }

    // /**
    //  * @return Niveau3[] Returns an array of Niveau3 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Niveau3
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
