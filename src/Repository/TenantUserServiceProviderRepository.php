<?php

namespace App\Repository;

use App\Entity\TenantUserServiceProvider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TenantUserServiceProvider|null find($id, $lockMode = null, $lockVersion = null)
 * @method TenantUserServiceProvider|null findOneBy(array $criteria, array $orderBy = null)
 * @method TenantUserServiceProvider[]    findAll()
 * @method TenantUserServiceProvider[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TenantUserServiceProviderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TenantUserServiceProvider::class);
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
