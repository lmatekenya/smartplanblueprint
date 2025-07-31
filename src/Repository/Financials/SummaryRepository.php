<?php

namespace App\Repository\Financials;

use App\Entity\Financials\Summary;
use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SummaryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Summary::class);
    }

    public function findForMerchantByDateRange(Merchant $merchant, \DateTimeInterface $start, \DateTimeInterface $end): ?TrialBalance
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.merchant = :merchant')
            ->andWhere('t.periodStart >= :start')
            ->andWhere('t.periodEnd <= :end')
            ->setParameter('merchant', $merchant)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function createOrUpdateSummary(
        Merchant $merchant,
        array $metrics,
        \DateTimeInterface $periodStart,
        \DateTimeInterface $periodEnd
    ): Summary {
        $trialBalance = $this->findForMerchantByDateRange($merchant, $periodStart, $periodEnd) ?? new Summary();

        $trialBalance->setMerchant($merchant)
            ->setDeposits($metrics['deposits'] ?? '0.00')
            ->setCommissionCredited($metrics['commission_credited'] ?? '0.00')
            ->setReversals($metrics['reversals'] ?? '0.00')
            ->setElectricitySales($metrics['electricity_sales'] ?? '0.00')
            ->setMascomSales($metrics['mascom_sales'] ?? '0.00')
            ->setOrangeSales($metrics['orange_sales'] ?? '0.00')
            ->setWithdrawals($metrics['withdrawals'] ?? '0.00')
            ->setMerchantCommission($metrics['merchant_commission'] ?? '0.00')
            ->setPeriodStart($periodStart)
            ->setPeriodEnd($periodEnd);

        $this->_em->persist($trialBalance);
        $this->_em->flush();

        return $trialBalance;
    }


}
