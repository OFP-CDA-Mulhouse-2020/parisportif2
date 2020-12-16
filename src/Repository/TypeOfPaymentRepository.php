<?php

namespace App\Repository;

use App\Entity\TypeOfPayment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeOfPayment|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfPayment|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfPayment[]    findAll()
 * @method TypeOfPayment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfPaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOfPayment::class);
    }

    // /**
    //  * @return TypeOfPayment[] Returns an array of TypeOfPayment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOfPayment
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
