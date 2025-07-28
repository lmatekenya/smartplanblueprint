<?php

// src/Repository/UserRepository.php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findByMerchant(int $merchantId): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActiveByMerchant(int $merchantId): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.merchant = :merchantId')
            ->andWhere('u.isActive = true')
            ->setParameter('merchantId', $merchantId)
            ->getQuery()
            ->getResult();
    }
}
