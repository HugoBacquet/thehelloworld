<?php

namespace App\Repository;

use App\Entity\Practitioner;
use App\Entity\PractitionerImportanceCriterion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PractitionerImportanceCriterion|null find($id, $lockMode = null, $lockVersion = null)
 * @method PractitionerImportanceCriterion|null findOneBy(array $criteria, array $orderBy = null)
 * @method PractitionerImportanceCriterion[]    findAll()
 * @method PractitionerImportanceCriterion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PractitionerImportanceCriterionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PractitionerImportanceCriterion::class);
    }


    public function getAverageNote($practitioner, $criterions) {
        $query = $this->createQueryBuilder('p')
            ->where('p.practitioner = :practitioner')
            ->setParameter('practitioner', $practitioner)
            ->getQuery()
            ->getResult();

        $count = [];
        $notes = [];

        foreach ($criterions as $criterion) {
            if (!isset($count[$criterion->getName()])) {
                $count[$criterion->getName()] = 0;
                $notes[$criterion->getName()] = 0;
            }
            foreach ($query as $note) {
                if ($criterion->getName() === $note->getCriterion()->getName()) {
                    $count[$criterion->getName()]++;
                    $notes[$criterion->getName()] += $note->getNote();
                }
            }
            if($count[$criterion->getName()] > 0) {
                $notes[$criterion->getName()] /= $count[$criterion->getName()];
            }
        }
        return $notes;
    }

    // /**
    //  * @return PractitionerImportanceCriterion[] Returns an array of PractitionerImportanceCriterion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PractitionerImportanceCriterion
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
