<?php

namespace App\Repository\LimitsAndDocs;

use App\Entity\LimitsAndDocs\CreditLimit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CreditLimit>
 */
class CreditLimitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreditLimit::class);
    }

//    public function findActiveByMerchant(int $merchantId): array
//    {
//        return $this->createQueryBuilder('cl')
//            ->where('cl.merchant = :merchantId')
//            ->andWhere('cl.status = :status')
//            ->setParameter('merchantId', $merchantId)
//            ->setParameter('status', 'Approved')
//            ->orderBy('cl.expiryDate', 'DESC')
//            ->getQuery()
//            ->getResult();
//    }

    public function findActiveByMerchant(int $merchantId): array
    {
        return $this->createQueryBuilder('cl')
            ->where('cl.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->orderBy('cl.expiryDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
