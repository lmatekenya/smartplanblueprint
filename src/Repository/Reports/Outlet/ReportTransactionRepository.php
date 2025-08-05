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

    public function findByFilters($merchantId, $startDate = null, $endDate = null, $detail = null)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->orderBy('t.dateTime', 'DESC');

        // Handle date filters (only if detail is empty)
        if (empty($detail)) {
            if ($startDate) {
                try {
                    $startDateObj = new \DateTime($startDate);
                    $startDateObj->setTime(0, 0, 0);
                    $qb->andWhere('t.dateTime >= :startDate')
                        ->setParameter('startDate', $startDateObj);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Invalid start date format');
                }
            }

            if ($endDate) {
                try {
                    $endDateObj = new \DateTime($endDate);
                    $endDateObj->setTime(23, 59, 59);
                    $qb->andWhere('t.dateTime <= :endDate')
                        ->setParameter('endDate', $endDateObj);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Invalid end date format');
                }
            }
        }

        // Handle detail search
        if (!empty($detail)) {
            $qb->andWhere('LOWER(t.detail) LIKE LOWER(:detail)')
                ->setParameter('detail', '%'.addcslashes($detail, '%_').'%');
        }

        return $qb->getQuery()->getResult();
    }

//    public function findByFilters($merchantId, $startDate = null,$endDate = null, $detail = null)
//    {
//        $qb = $this->createQueryBuilder('t')
//            ->where('t.merchant = :merchantId')
//            ->setParameter('merchantId', $merchantId)
//            ->orderBy('t.dateTime', 'DESC');
//
////        if ($date) {
////            $dateStart = \DateTime::createFromFormat('Y-m-d', $date);
////            $dateStart->setTime(0, 0, 0);
////
////            $dateEnd = clone $dateStart;
////            $dateEnd->modify('+1 day');
////
////            $qb->andWhere('t.dateTime >= :startDate')
////                ->andWhere('t.dateTime < :endDate')
////                ->setParameter('startDate', $dateStart)
////                ->setParameter('endDate', $dateEnd);
////        }
//
//        if ($startDate && $endDate) {
//            $startDateObj = \DateTime::createFromFormat('Y-m-d', $startDate);
//            $startDateObj->setTime(0, 0, 0);
//
//            $endDateObj = \DateTime::createFromFormat('Y-m-d', $endDate);
//            $endDateObj->setTime(23, 59, 59);
//
//            $qb->andWhere('t.dateTime BETWEEN :startDate AND :endDate')
//                ->setParameter('startDate', $startDateObj)
//                ->setParameter('endDate', $endDateObj);
//        } elseif ($startDate) {
//            $startDateObj = \DateTime::createFromFormat('Y-m-d', $startDate);
//            $startDateObj->setTime(0, 0, 0);
//
//            $qb->andWhere('t.dateTime >= :startDate')
//                ->setParameter('startDate', $startDateObj);
//        } elseif ($endDate) {
//            $endDateObj = \DateTime::createFromFormat('Y-m-d', $endDate);
//            $endDateObj->setTime(23, 59, 59);
//
//            $qb->andWhere('t.dateTime <= :endDate')
//                ->setParameter('endDate', $endDateObj);
//        }
//
//        if ($detail) {
//            $qb->andWhere('t.detail LIKE :detail')
//                ->setParameter('detail', '%'.addcslashes($detail, '%_').'%');
//        }
//
//        return $qb->getQuery()->getResult();
//    }



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
