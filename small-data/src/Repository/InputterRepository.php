<?php

namespace App\Repository;

use App\Entity\Inputter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Inputter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inputter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inputter[]    findAll()
 * @method Inputter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InputterRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Inputter::class);
    }

    /**
     * @return Inputter[]
     */
    public function findAllValidatorsJson(){
        return $this->createQueryBuilder('inp')
//str_replace('"', '', )
            ->andWhere(json_encode('inp.roles LIKE :role'))
            ->setParameter('role', '%ROLE_VALIDATOR%' )
            ->getQuery()
            ->execute();

    }
    

    /**
     * @return Inputter[]
     */
    public function findAllValidators(){
        return $this->createQueryBuilder('inp')
            ->andWhere('inp.username LIKE :username1')
            ->setParameter('username1', '%1%')
            ->orWhere('inp.username LIKE :username2')

            ->setParameter('username2', '%2%')
            ->getQuery()
            ->execute();

    }



//    /**
//     * @return Inputter[] Returns an array of Inputter objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Inputter
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
