<?php

namespace App\Repository\Reports\Outlet;

use App\Entity\Reports\Outlet\ReportsTransaction;
use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReportTransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportsTransaction::class);
    }

//    public function findByFilters(?string $date, ?string $detail)
//    {
//        $qb = $this->createQueryBuilder('t')
//            ->orderBy('t.dateTime', 'DESC');
//
//        if ($date) {
//            $qb->andWhere('t.dateTime LIKE :date')
//                ->setParameter('date', $date.'%');
//        }
//
//        if ($detail) {
//            $qb->andWhere('t.detail LIKE :detail')
//                ->setParameter('detail', '%'.$detail.'%');
//        }
//
//        return $qb->getQuery()->getResult();
//    }

    // src/Repository/TransactionRepository.php
//    public function findByFilters(int $outletId, ?string $date, ?string $detail)
//    {
//        $qb = $this->createQueryBuilder('t')
//            ->where('t.outletId = :outletId')
//            ->setParameter('outletId', $outletId)
//            ->orderBy('t.dateTime', 'DESC');
//
//        if ($date) {
//            $qb->andWhere('t.dateTime LIKE :date')
//                ->setParameter('date', $date.'%');
//        }
//
//        if ($detail) {
//            $qb->andWhere('t.detail LIKE :detail')
//                ->setParameter('detail', '%'.$detail.'%');
//        }
//
//        return $qb->getQuery()->getResult();
//    }

    public function findByFilters($outletId, $date = null, $detail = null)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.outletId = :outletId')
            ->setParameter('outletId', $outletId)
            ->orderBy('t.dateTime', 'DESC');

        if ($date) {
            $dateStart = new \DateTime($date);
            $dateEnd = clone $dateStart;
            $dateEnd->modify('+1 day');

            $qb->andWhere('t.dateTime >= :dateStart')
                ->andWhere('t.dateTime < :dateEnd')
                ->setParameter('dateStart', $dateStart)
                ->setParameter('dateEnd', $dateEnd);
        }

        if ($detail) {
            $qb->andWhere('t.detail LIKE :detail')
                ->setParameter('detail', '%'.$detail.'%');
        }

        return $qb->getQuery()->getResult();
    }

    public function getSalesTotal(array $transactions = null): float
    {
        if ($transactions === null) {
            $qb = $this->createQueryBuilder('t')
                ->select('SUM(t.value) as total')
                ->where('t.type = :type')
                ->setParameter('type', 'Sale');

            return (float) $qb->getQuery()->getSingleScalarResult() ?? 0;
        }

        return array_reduce($transactions, function($carry, $txn) {
            return $carry + ($txn->getType() === 'Sale' ? (float) $txn->getValue() : 0);
        }, 0);
    }

    public function getFailedCount(array $transactions = null): int
    {
        if ($transactions === null) {
            return $this->count(['status' => 'Failed']);
        }

        return count(array_filter($transactions, function($txn) {
            return $txn->getStatus() === 'Failed';
        }));
    }

    public function getCommissionTotal(array $transactions = null): float
    {
        if ($transactions === null) {
            $qb = $this->createQueryBuilder('t')
                ->select('SUM(t.value) as total')
                ->where('t.type = :type')
                ->setParameter('type', 'Commission');

            return (float) $qb->getQuery()->getSingleScalarResult() ?? 0;
        }

        return array_reduce($transactions, function($carry, $txn) {
            return $carry + ($txn->getType() === 'Commission' ? (float) $txn->getValue() : 0);
        }, 0);
    }

    public function getDepositsTotal(array $transactions = null): float
    {
        if ($transactions === null) {
            $qb = $this->createQueryBuilder('t')
                ->select('SUM(t.value) as total')
                ->where('t.type = :type')
                ->setParameter('type', 'Deposit');

            return (float) $qb->getQuery()->getSingleScalarResult() ?? 0;
        }

        return array_reduce($transactions, function($carry, $txn) {
            return $carry + ($txn->getType() === 'Deposit' ? (float) $txn->getValue() : 0);
        }, 0);
    }

    public function getReversalsTotal(array $transactions = null): float
    {
        if ($transactions === null) {
            $qb = $this->createQueryBuilder('t')
                ->select('SUM(t.value) as total')
                ->where('t.type = :type')
                ->setParameter('type', 'Reversal');

            return (float) $qb->getQuery()->getSingleScalarResult() ?? 0;
        }

        return array_reduce($transactions, function($carry, $txn) {
            return $carry + ($txn->getType() === 'Reversal' ? (float) $txn->getValue() : 0);
        }, 0);
    }
}
