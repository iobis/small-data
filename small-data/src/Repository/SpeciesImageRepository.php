<?php

namespace App\Repository;

use App\Entity\SpeciesImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SpeciesImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpeciesImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpeciesImage[]    findAll()
 * @method SpeciesImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpeciesImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SpeciesImage::class);
    }

//    /**
//     * @return SpeciesImage[] Returns an array of SpeciesImage objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpeciesImage
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
