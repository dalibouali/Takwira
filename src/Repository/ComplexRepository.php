<?php

namespace App\Repository;

use App\Entity\Complex;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Complex|null find($id, $lockMode = null, $lockVersion = null)
 * @method Complex|null findOneBy(array $criteria, array $orderBy = null)
 * @method Complex[]    findAll()
 * @method Complex[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComplexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Complex::class);
    }

    /**
     * @return Complex[] Returns an array of Complex objects
     *
     */

    public function findByville($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ville = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Complex
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
