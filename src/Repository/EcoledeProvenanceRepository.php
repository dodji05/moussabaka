<?php

namespace App\Repository;

use App\Entity\EcoledeProvenance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EcoledeProvenance>
 *
 * @method EcoledeProvenance|null find($id, $lockMode = null, $lockVersion = null)
 * @method EcoledeProvenance|null findOneBy(array $criteria, array $orderBy = null)
 * @method EcoledeProvenance[]    findAll()
 * @method EcoledeProvenance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EcoledeProvenanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EcoledeProvenance::class);
    }

//    /**
//     * @return EcoledeProvenance[] Returns an array of EcoledeProvenance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EcoledeProvenance
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
