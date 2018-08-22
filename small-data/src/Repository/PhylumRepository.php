<?php

namespace App\Repository;

use App\Entity\Phylum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Phylum|null find($id, $lockMode = null, $lockVersion = null)
 * @method Phylum|null findOneBy(array $criteria, array $orderBy = null)
 * @method Phylum[]    findAll()
 * @method Phylum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PhylumRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Phylum::class);
    }

//    /**
//     * @return Phylum[] Returns an array of Phylum objects
//     */
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
    public function findOneBySomeField($value): ?Phylum
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
