<?php

namespace App\Repository;

use App\Entity\AdditionalCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AdditionalCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdditionalCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdditionalCriterion[]    findAll()
 * @method AdditionalCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdditionalCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AdditionalCriterion::class);
    }

    // /**
    //  * @return AdditionalCriterion[] Returns an array of AdditionalCriterion objects
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
    public function findOneBySomeField($value): ?AdditionalCriterion
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
