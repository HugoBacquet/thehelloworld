<?php

namespace App\Repository;

use App\Entity\ImportanceCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ImportanceCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method ImportanceCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method ImportanceCriterion[]    findAll()
 * @method ImportanceCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImportanceCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ImportanceCriterion::class);
    }

    // /**
    //  * @return ImportanceCriterion[] Returns an array of ImportanceCriterion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ImportanceCriterion
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
