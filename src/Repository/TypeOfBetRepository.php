<?php

namespace App\Repository;

use App\Entity\TypeOfBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeOfBet|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfBet|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfBet[]    findAll()
 * @method TypeOfBet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfBetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOfBet::class);
    }

    // /**
    //  * @return TypeOfBet[] Returns an array of TypeOfBet objects
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
    public function findOneBySomeField($value): ?TypeOfBet
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
