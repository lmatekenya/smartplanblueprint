<?php

namespace App\Repository\Financials;


use App\Entity\Financials\Deposits\Deposits;
use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Deposits>
 */
class DepositsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deposits::class);
    }

    // BASIC CRUD OPERATIONS

//    public function findByMerchant(int $merchantId): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.merchant = :merchantId')
//            ->setParameter('merchantId', $merchantId)
//            ->orderBy('d.depositDate', 'DESC')
//            ->getQuery()
//            ->getResult();
//    }
    public function getTotalForMerchant(Merchant $merchant, \DateTimeInterface $start, \DateTimeInterface $end): string
    {
        $result = $this->createQueryBuilder('d')
            ->select('SUM(d.amount)')
            ->where('d.merchant = :merchant')
            ->andWhere('d.createdAt BETWEEN :start AND :end')
            ->setParameter('merchant', $merchant)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ?: '0.00';
    }
    public function findByMerchant(int $merchantId): array
    {
        return $this->createQueryBuilder('d')
            ->addSelect('ma', 'b') // Ensure related entities are hydrated
            ->join('d.merchantAccount', 'ma')
            ->join('d.bank', 'b')
            ->where('ma.id = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->getQuery()
            ->getResult();
    }

//    public function findByMerchant(int $merchantId): array
//    {
//        return $this->createQueryBuilder('d')
//            ->join('d.merchantAccount', 'ma')
//            ->join('d.bank', 'b')
//            ->where('ma.id = :merchantId')
//            ->setParameter('merchantId', $merchantId)
//            ->select([
//                'd',
//                'ma',
//                'b',
//                'd.id',
//                'd.transactionDate',
//                'ma.accountName as merchantAccount',
//                'b.name as bank',
//                'd.depositDate',
//                'd.amount',
//                'd.depositMethod'
//            ])
//            ->getQuery()
//            ->getResult();
//    }
    public function findByMerchantAndDateRange(Merchant $merchant, \DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.merchant = :merchant')
            ->andWhere('d.depositDate BETWEEN :start AND :end')
            ->setParameter('merchant', $merchant)
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('d.depositDate', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function save(Deposits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Deposits $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    // QUERY EXAMPLES

    /**
     * Find all deposits for a specific merchant
     */
//    public function findByMerchant(string $merchantAccount): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.merchantAccount = :merchant')
//            ->setParameter('merchant', $merchantAccount)
//            ->orderBy('d.depositDate', 'DESC')
//            ->getQuery()
//            ->getResult();
//    }

    /**
     * Find deposits within a date range
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.depositDate BETWEEN :start AND :end')
            ->setParameter('start', $startDate)
            ->setParameter('end', $endDate)
            ->orderBy('d.depositDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Get total deposits amount by merchant
     */
    public function getTotalDepositsByMerchant(string $merchantAccount): float
    {
        $result = $this->createQueryBuilder('d')
            ->select('SUM(d.amount) as total')
            ->andWhere('d.merchantAccount = :merchant')
            ->setParameter('merchant', $merchantAccount)
            ->getQuery()
            ->getSingleScalarResult();

        return $result ? (float)$result : 0.0;
    }

    /**
     * Find deposits by bank and method
     */
    public function findByBankAndMethod(string $bankName, string $method): array
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.bankName = :bank')
            ->andWhere('d.depositMethod = :method')
            ->setParameter('bank', $bankName)
            ->setParameter('method', $method)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get paginated deposits (for UI tables)
     */
    public function getPaginatedDeposits(int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('d')
            ->orderBy('d.depositDate', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get deposits summary statistics
     */
    public function getDepositStats(): array
    {
        return $this->createQueryBuilder('d')
            ->select([
                'COUNT(d.id) as total_count',
                'SUM(d.amount) as total_amount',
                'AVG(d.amount) as average_amount',
                'MIN(d.depositDate) as earliest_date',
                'MAX(d.depositDate) as latest_date'
            ])
            ->getQuery()
            ->getSingleResult();
    }

    // ADVANCED: Using the query builder pattern
    public function createSearchQueryBuilder(
        ?string $merchant = null,
        ?string $bank = null,
        ?\DateTimeInterface $fromDate = null,
        ?\DateTimeInterface $toDate = null,
        ?string $method = null
    ): QueryBuilder {
        $qb = $this->createQueryBuilder('d')
            ->orderBy('d.depositDate', 'DESC');

        if ($merchant) {
            $qb->andWhere('d.merchantAccount = :merchant')
                ->setParameter('merchant', $merchant);
        }

        if ($bank) {
            $qb->andWhere('d.bankName = :bank')
                ->setParameter('bank', $bank);
        }

        if ($fromDate) {
            $qb->andWhere('d.depositDate >= :fromDate')
                ->setParameter('fromDate', $fromDate);
        }

        if ($toDate) {
            $qb->andWhere('d.depositDate <= :toDate')
                ->setParameter('toDate', $toDate);
        }

        if ($method) {
            $qb->andWhere('d.depositMethod = :method')
                ->setParameter('method', $method);
        }

        return $qb;
    }
}
