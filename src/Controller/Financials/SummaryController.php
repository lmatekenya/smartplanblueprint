<?php

namespace App\Controller\Financials;

use App\Entity\Merchant;
use App\Repository\Financials\CommissionRepository;
use App\Repository\Financials\DepositsRepository;
use App\Repository\Financials\FinancialsRepository;
use App\Repository\Financials\ReversalsRepository;
use App\Repository\Financials\WithdrawalsRepository;
use App\Service\DateRangeHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/merchant')]
class SummaryController extends AbstractController
{
    #[Route('/financials/{id}/summary', name: 'financials_summary')]
    public function summary(
        Merchant $merchant,
        Request $request,
        DateRangeHelper $dateRangeHelper,
        DepositsRepository $depositRepository,
        CommissionRepository $commissionRepository,
        ReversalsRepository $reversalRepository,
        FinancialsRepository $salesRepository,
         WithdrawalsRepository $withdrawalRepository
    ): Response {
        $range = $request->query->get('range', 'week');
        $dateRange = $dateRangeHelper->getDateRange($range);

        // Fetch metrics from repositories
        $metrics = [
            'deposits' => $depositRepository->getTotalForMerchant(
                $merchant,
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'commission_credited' => $commissionRepository->getCreditedTotalForMerchant(
                $merchant,
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'reversals' => $reversalRepository->getTotalReversalsForMerchant(
                $merchant,
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'electricity_sales' => $salesRepository->getTotalByTypeForMerchant(
                $merchant,
                'electricity',
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'mascom_sales' => $salesRepository->getTotalByTypeForMerchant(
                $merchant,
                'mascom_pinless',
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'orange_sales' => $salesRepository->getTotalByTypeForMerchant(
                $merchant,
                'orange_pinless',
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'withdrawals' => $withdrawalRepository->getTotalForMerchant(
                $merchant,
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            ),
            'merchant_commission' => $commissionRepository->getEarnedTotalForMerchant(
                $merchant,
                $dateRange->getStartDate(),
                $dateRange->getEndDate()
            )
        ];

        // Calculate totals
        $startingBalance = bcadd(
            $metrics['deposits'],
            bcadd($metrics['commission_credited'], $metrics['reversals'], 2),
            2
        );

        $salesTotal = bcadd(
            $metrics['electricity_sales'],
            bcadd($metrics['mascom_sales'], $metrics['orange_sales'], 2),
            2
        );

        $closingBalance = bcsub(
            bcadd($startingBalance, $salesTotal, 2),
            $metrics['withdrawals'],
            2
        );

        return $this->render('financials/summary.html.twig', [
            'merchant' => $merchant,
            'dateRange' => $dateRange,
            'metricTotals' => array_merge($metrics, [
                'sales' => $salesTotal, // Add this for the metrics grid
                'commission' => $metrics['merchant_commission'] // Add this for the metrics grid
            ]),
            'starting_balance_total' => $startingBalance,
            'sales_total' => $salesTotal,
            'total_sales' => $salesTotal,
            'closing_balance' => $closingBalance,
            'commission_total' => $metrics['merchant_commission']
        ]);
//        return $this->render('financials/summary.html.twig', [
//            'merchant' => $merchant,
//            'dateRange' => $dateRange,
//            'metricTotals' => $metrics,
//            'starting_balance_total' => $startingBalance,
//            'sales_total' => $salesTotal,
//            'total_sales' => $salesTotal,
//            'closing_balance' => $closingBalance,
//            'commission_total' => $metrics['merchant_commission']
//        ]);
    }
}
