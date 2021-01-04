<?php

namespace App\Repository;

use App\Entity\WebsiteWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method WebsiteWallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method WebsiteWallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method WebsiteWallet[]    findAll()
 * @method WebsiteWallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WebsiteWalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, WebsiteWallet::class);
    }

    // /**
    //  * @return WebsiteWallet[] Returns an array of WebsiteWallet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WebsiteWallet
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
