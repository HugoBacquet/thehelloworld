<?php

namespace App\Repository;

use App\Entity\Speciality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Speciality|null find($id, $lockMode = null, $lockVersion = null)
 * @method Speciality|null findOneBy(array $criteria, array $orderBy = null)
 * @method Speciality[]    findAll()
 * @method Speciality[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpecialityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Speciality::class);
    }
    public function getChoices()
    {
        $specialities = $this->findAll();
        $choices = [];
        foreach ($specialities as $speciality){
            $choices[$speciality->getName()] = $speciality->getName();
        }
        return $choices;
    }

    public function getMains() {

        $query =  $this->createQueryBuilder('p')
            ->select('p.id, p.name')
            ->where('p.level = 1')
        ;

        $queryResult = $query
            ->getQuery()
            ->getArrayResult();

        $result = [];

        foreach ($queryResult as $speciality) {
            $result[$speciality["name"]] = $speciality["id"];
        }

        return $result;
    }

    public function getGroupedSpecialities() {
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
