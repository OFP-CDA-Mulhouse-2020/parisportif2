<?php

namespace App\Repository;

use App\Entity\CardIdFile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CardIdFile|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardIdFile|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardIdFile[]    findAll()
 * @method CardIdFile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardIdFileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardIdFile::class);
    }

    // /**
    //  * @return CardIdFile[] Returns an array of CardIdFile objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CardIdFile
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
