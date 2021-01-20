<?php

namespace App\Repository;

use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Payment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Payment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Payment[]    findAll()
 * @method Payment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Payment::class);
    }

    // /**
    //  * @return Payment[] Returns an array of Payment objects
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
    public function findOneBySomeField($value): ?Payment
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAmountOfLastWeek(int $walletId, int $typeOfPaymentId): ?array
    {
        return $this->createQueryBuilder('payment')
            ->select("SUM(payment.sum)/100 AS amountOfLastWeek")
            ->where('payment.wallet = :walletId')
            ->andWhere('payment.typeOfPayment = :typeOfPaymentId')
            ->andWhere('payment.datePayment BETWEEN :last7Days AND :today')
            ->setParameters(['walletId' => $walletId,
                'typeOfPaymentId' => $typeOfPaymentId,
                'last7Days' => date('Y-m-d h:i:s', strtotime("-7 days")),
                'today' => date('Y-m-d h:i:s')
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
