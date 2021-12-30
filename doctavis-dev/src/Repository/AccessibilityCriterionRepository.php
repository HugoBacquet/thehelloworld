<?php

namespace App\Repository;

use App\Entity\AccessibilityCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccessibilityCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccessibilityCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccessibilityCriterion[]    findAll()
 * @method AccessibilityCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccessibilityCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccessibilityCriterion::class);
    }
    public function getChoices()
    {
        $accessibilities = $this->findAll();
        $choices = [];
        foreach ($accessibilities as $accessibility){
            $choices[$accessibility->getName()] = $accessibility->getName();
        }
        return $choices;
    }

    public function getGroupedCriterions() {
        $query =  $this->createQueryBuilder('ac')
            ->select('ac.name AS name')
            ->where('ac.level = 1')
        ;
        $queryResult = $query
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($queryResult as $parent) {
            $query =  $this->createQueryBuilder('ac')
                ->select('
                    ac.id AS id, 
                    ac.name AS name
                ')
                ->where('ac.level = 2')
                ->innerJoin('ac.parent', 'criterion')
                ->andWhere('criterion.name = :parentName')
                ->setParameter('parentName', $parent['name'])
            ;

            $children = $query->getQuery()->getArrayResult();

            foreach ($children as $child) {
                $result[$parent["name"]][$child["name"]] = $child["id"];
            }
        }

        return $result;
    }

    // /**
    //  * @return AccessibilityCriterion[] Returns an array of AccessibilityCriterion objects
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
    public function findOneBySomeField($value): ?AccessibilityCriterion
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
