<?php

namespace App\Repository;

use App\Entity\Pathology;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pathology|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pathology|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pathology[]    findAll()
 * @method Pathology[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PathologyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pathology::class);
    }

    public function getGroupedPathologies() {
        $query =  $this->createQueryBuilder('p')
            ->select('p.name AS name')
            ->where('p.level = 1')
            ;
        $queryResult = $query
            ->getQuery()
            ->getResult();

        $result = [];

        foreach ($queryResult as $parent) {
            $query =  $this->createQueryBuilder('p')
                ->select('p.id AS id, p.name AS name')
                ->where('p.level = 2')
                ->innerJoin('p.parent', 'pathology')
                ->andWhere('pathology.name = :parentName')
                ->setParameter('parentName', $parent['name'])
            ;

            $children = $query->getQuery()->getArrayResult();

            foreach ($children as $child) {
                $result[$parent["name"]][$child["name"]] = $child["id"];
            }
        }

        return $result;
    }
}
