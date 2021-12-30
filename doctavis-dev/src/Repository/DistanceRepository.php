<?php

namespace App\Repository;

use App\Entity\Distance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Distance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Distance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Distance[]    findAll()
 * @method Distance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DistanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Distance::class);
    }

     /**
      * @return Distance[] Returns an array of Distance objects
      */
    public function findByVille($origin, $destination)
    {
        return $this->createQueryBuilder('d')
            ->orWhere('d.origin = :origin AND d.destination = :destination')
            ->orWhere('d.origin = :destination AND d.destination = :origin')
            ->setParameter('origin', $origin)
            ->setParameter('destination', $destination)
            ->getQuery()
            ->getMaxResults(1)
        ;
    }


    /*
    public function findOneBySomeField($value): ?Distance
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
