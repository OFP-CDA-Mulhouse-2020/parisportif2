<?php

namespace App\Repository;

use App\Entity\BankAccountFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BankAccountFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method BankAccountFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method BankAccountFile[]    findAll()
 * @method BankAccountFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankAccountFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BankAccountFile::class);
    }

    // /**
    //  * @return BankAccountFile[] Returns an array of BankAccountFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BankAccountFile
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
