<?php

namespace App\Repository;

use App\Entity\Bet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bet[]    findAll()
 * @method Bet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bet::class);
    }

    // /**
    //  * @return Bet[] Returns an array of Bet objects
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
    public function findOneBySomeField($value): ?Bet
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findAllSimpleBet(): array
    {
        return $this->createQueryBuilder('bet')
            ->leftJoin('bet.typeOfBet', 'typeOfBet')
            ->leftJoin('bet.event', 'event')
            ->where('bet.betOpened = true')
            ->andwhere('typeOfBet.betType = :type1 OR typeOfBet.betType = :type2')
            ->setParameters(['type1' => '1N2', 'type2' => '1-2'])
            ->getQuery()
            ->getResult();
    }

    public function findSimpleBetBySport(string $sports): array
    {
        return $this->createQueryBuilder('bet')
            ->leftJoin('bet.typeOfBet', 'typeOfBet')
            ->leftJoin('bet.event', 'event')
            ->leftJoin('event.sport', 'sport')
            ->where('bet.betOpened = true')
            ->andwhere('typeOfBet.betType = :type1 OR typeOfBet.betType = :type2')
            ->andwhere('event.sport = sport')
            ->andwhere('sport.name = :sportName')
            ->setParameters(['sportName' => $sports, 'type1' => '1N2', 'type2' => '1-2'])
            ->getQuery()
            ->getResult();
    }
}
