<?php

namespace App\Repository\Financials;


use App\Entity\Financials\Sales;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sales>
 */
class FinancialsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sales::class);
    }

    public function findGroupedByProvider(int $merchantId): array
    {
        return $this->createQueryBuilder('s')
            ->select('s.saleType', 's.provider', 'SUM(s.total) as total')
            ->where('s.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->groupBy('s.saleType', 's.provider')
            ->orderBy('s.saleType', 'ASC')
            ->getQuery()
            ->getResult();
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
    public function findDepositsByMerchant(int $merchantId): float
    {
        $result = $this->createQueryBuilder('s')
            ->select('SUM(s.depositAmount) as total')
            ->where('s.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float)$result : 0.0;
    }

    public function findBySaleType(string $saleType): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.saleType = :saleType')
            ->setParameter('saleType', $saleType)
            ->orderBy('s.saleDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findByProvider(string $provider): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.provider = :provider')
            ->setParameter('provider', $provider)
            ->orderBy('s.saleDate', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findRecentSales(int $limit = 10): array
    {
        return $this->createQueryBuilder('s')
            ->orderBy('s.saleDate', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function save(Sales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sales $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
