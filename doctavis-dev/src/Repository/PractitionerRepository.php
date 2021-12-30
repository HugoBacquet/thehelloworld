<?php

namespace App\Repository;

use App\Constant\ConsultationTypes;
use App\Constant\Sex;
use App\Entity\Language;
use App\Entity\Practitioner;
use App\Entity\Speciality;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Practitioner|null find($id, $lockMode = null, $lockVersion = null)
 * @method Practitioner|null findOneBy(array $criteria, array $orderBy = null)
 * @method Practitioner[]    findAll()
 * @method Practitioner[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PractitionerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Practitioner::class);
    }

    /**
     * @param $params
     * @return int|mixed|string
     */
    public function getData($params)
    {
        $sex = (isset($params['sex']) and $params["sex"] !== "Peu importe") ? '%' . $params['sex'] . '%' : null;
        $consultationType = (isset($params['consultationType']) and !in_array(1, $params["consultationType"])) ? '%' . ConsultationTypes::getNameByNumber($params["consultationType"]) . '%' : null;
        $postalCodes = isset($params['postalCodes']) ? $params['postalCodes'] : null;
        $pathology = isset($params['pathologies']) ? $params['pathologies'] : null;
        $speciality = null;
        if (null == $pathology) {
            $speciality = isset($params['specialities']) ? $params['specialities'] : null;
        }

        $query = $this->createQueryBuilder('p')
            ->distinct(true)
            ->andWhere('p.isEnabled = 1');

        if ($speciality) {
            $query = $query
                ->andWhere('speciality.id = :speciality')
                ->innerJoin(Speciality::class, 'speciality', 'WITH', ':speciality MEMBER OF p.specialities')
                ->setParameter('speciality', $params["specialities"]);
        }


        if ($postalCodes) {
            $query = $query
                ->andWhere('p.postalCode IN (:postalCodes)')
                ->setParameter('postalCodes', $postalCodes);
        }

        if (isset($params["language"])) {
            $where = "";
            foreach ($params["language"] as $key => $value) {
                $query = $query
                    ->leftJoin(Language::class, 'language_' . $key, 'WITH', ':language_' . $key . ' MEMBER OF p.languages')
                    ->setParameter('language_' . $key, $value->getId());
                $where .= "language_" . $key . ".id = :language_" . $key . " OR ";
            }
            $where .= substr($where, 0, -4);
            $query = $query->andWhere($where);
        }

        if ($sex) {
            $query = $query
                ->andWhere('p.sex LIKE :sex')
                ->setParameter('sex', $sex);
        }

        if ($consultationType) {
            $where = "";
            foreach ($params["consultationType"] as $key => $value) {
                $query = $query
                    ->setParameter('consultationType_' . $key, '%'.ConsultationTypes::getNameByNumber($value).'%');
                $where .= 'p.consultationTypes LIKE :consultationType_' . $key . ' OR ';
            }
            $where .= substr($where, 0, -4);
            $query = $query->andWhere($where);
        }

        if (isset($params["pathologies"])) {
            $query = $query
                ->andWhere(':pathology MEMBER OF p.pathologies')
                ->setParameter('pathology', $params["pathologies"]);
        }

        $query = $query->setMaxResults(3);

        return $query->getQuery()->getResult();
    }

//    /**
//     * @return array
//     */
//    public function getProfessions(){
//        $query = $this->createQueryBuilder('p')
//            ->leftJoin(Profession::class, 'profession','WITH',  'p.profession = profession.id')
//            ->select('DISTINCT(profession.name)')
//            ->getQuery()
//            ->getResult();
//        $professions = [];
//        foreach ($query as $profession){
//            $value = $profession['1'];
//            $professions[$value] = $value;
//        }
//
//        return $professions;
//     }


//    /**
//     * @return array
//     */
//    public function getLangues($profession){
//        $profession = '%'. $profession . '%';
//        $query = $this->createQueryBuilder('p')
//            ->leftJoin(Profession::class, 'profession','WITH',  'p.profession = profession.id')
//            ->where('profession.name Like :name')
//            ->setParameter('name', $profession)
//            ->select('DISTINCT(p.languages)')
//            ->getQuery()
//            ->getResult();
//        $langues = [];
//        foreach ($query as $langue){
//            if (null != $langue['1'] && !strpos($langue['1'], ',')) {
//                $value = $langue['1'];
//                $langues[$value] = $value;
//            }
//            elseif (strpos($langue['1'], ',')) {
//                $languesArray = explode(',', $langue['1']);
//                foreach ($languesArray as $item) {
//                    $value = trim($item);
//                    $langues[$value] = $value;
//                }
//            }
//        }
//        return $langues;
//    }


    /**
     * @param $specialities
     * @param bool $flag
     * @return array|int|mixed|string
     */
    public function getPostalCodes($specialities, $flag = true)
    {
        $result = [];

        if ($flag) {
            foreach ($specialities as $speciality) {
                $query = $this->createQueryBuilder('p')
                    ->select('DISTINCT(p.postalCode)')
                    ->where('speciality.level = 2')
                    ->innerJoin('p.specialities', 'speciality', 'WITH', 'speciality.level = 2')
                    ->innerJoin('speciality.parent', 'specialityParent', 'WITH', 'speciality.parent = specialityParent.id')
                    ->andWhere('specialityParent.id = :id')
                    ->setParameter('id', $speciality);
                $queryResult = $query->getQuery()->getResult();
                if ($queryResult != []) {
                    $result = array_merge($result, $queryResult);
                }
            }

        } else {
            $query = $this->createQueryBuilder('p')
                ->select('DISTINCT(p.postalCode)');

            $result = $query->getQuery()->getResult();
        }
        $postalCodes = [];
        foreach ($result as $postalCode) {
            $value = $postalCode['1'];
            if (strlen($value) > 2) {
                $postalCodes[] = $value;
            }
        }
        $postalCodes = array_unique($postalCodes);

        return $postalCodes;
    }

    public function getPractitioners()
    {
        $practitioners = $this->findBy(['isEnabled' => true]);
        $results = [];
        foreach ($practitioners as $practitioner) {
            $results[$practitioner->getFirstname() . ' ' . $practitioner->getLastname()/*.' - '.$practitioner->getProfession()->getName()*/] = $practitioner;
        }
        return $results;
    }

    public function getPractitionersBySpecialty($speciality)
    {
        $qb = $this->createQueryBuilder("p")
            ->where(':speciality MEMBER OF p.specialities')
            ->setParameters(['speciality' => $speciality]);
        return $qb->getQuery()->getResult();
    }

    public function getByPostalAndPathology($postalCodes, $pathology)
    {
        $qb = $this->createQueryBuilder("p")
            ->where('p.postalCode IN (:postalCodes)')
            ->andWhere(':pathology MEMBER OF p.pathologies')
            ->setParameter('pathology', $pathology)
            ->setParameter('postalCodes', $postalCodes);
        return $qb->getQuery()->getResult();
    }
}
