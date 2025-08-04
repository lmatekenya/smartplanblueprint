<?php

namespace App\Repository\Financials;

use App\Entity\Financials\Commission;
use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CommissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commission::class);
    }
    public function findGroupedByTypeAndProvider(int $merchantId): array
    {
        return $this->createQueryBuilder('c')
            ->select(
                'c.commissionType as Type',
                'c.provider as Provider',
                'SUM(c.totalSales) as Sales',
                'SUM(c.merchantCommission) as Merchant'
            )
            ->where('c.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->groupBy('c.commissionType', 'c.provider')
            ->orderBy('c.commissionType', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getCreditedTotalForMerchant(Merchant $merchant, \DateTimeInterface $start, \DateTimeInterface $end): string
    {
        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.totalSales)') // Changed from 'amount' to 'commissionAmount'
            ->where('c.merchant = :merchant')
            ->andWhere('c.commissionType = :type')
            ->andWhere('c.createdAt BETWEEN :start AND :end')
            ->setParameter('merchant', $merchant)
            ->setParameter('type', 'credited')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?: '0.00';
    }

    public function getEarnedTotalForMerchant(Merchant $merchant, \DateTimeInterface $start, \DateTimeInterface $end): string
    {
        $result = $this->createQueryBuilder('c')
            ->select('SUM(c.totalSales)') // Changed from 'amount' to 'commissionAmount'
            ->where('c.merchant = :merchant')
            ->andWhere('c.commissionType = :type')
            ->andWhere('c.createdAt BETWEEN :start AND :end')
            ->setParameter('merchant', $merchant)
            ->setParameter('type', 'earned')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?: '0.00';
    }

}
