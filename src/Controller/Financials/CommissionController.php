<?php

namespace App\Controller\Financials;

use App\Repository\Financials\CommissionEarnedRepository;
use App\Repository\Financials\CommissionRepository;
use App\Repository\Financials\FinancialsRepository;
use App\Repository\MerchantRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/merchant')]
class CommissionController extends AbstractController
{
//    #[Route('/financials/{id}/commission', name: 'financials_commission')]
//    public function commission(int $id, FinancialsRepository $saleRepository,
//                          MerchantRepository $merchantRepository,
//                          Request $request, TransactionRepository $transactionRepository,
//    ): Response
//    {
//
//        // Get all transactions for grouping
//        $allTransactions = $transactionRepository->findAllWithMerchants();
//
//        $merchant = $merchantRepository->find($id);
//        if (!$merchant) {
//            throw $this->createNotFoundException('Merchant not found');
//        }
//
//        $groupedSales = $saleRepository->findGroupedByProvider($id);
//        $groupedCommission = $saleRepository->findGroupedByTypeAndProvider($id);
//        $groupedTransactions = [];
//        // Calculate metric totals
//        $metricTotals = [
//            'opening' => 0,
//            'sales' => array_sum(array_column($groupedSales, 'total')),
//            'deposits' => 0, // You'll need to calculate this from your data
//            'commission' => array_sum(array_column($groupedCommission, 'merchantCommission')),
//            'reversals' => 0, // Calculate if you have reversal data
//            'closing' => 0,
//        ];
//
//        foreach ($allTransactions as $transaction) {
//            $merchant = $transaction->getMerchant();
//            $merchantId = $merchant->getId();
//
//            if (!isset($groupedTransactions[$merchantId])) {
//
//                $metricTotals['opening'] += $transaction->getOpeningBalance();
//                $metricTotals['deposits'] += $transaction->getDeposits();
//                $metricTotals['sales'] += $transaction->getSales();
//                $metricTotals['closing'] += $transaction->getClosingBalance();
//            }
//        }
//
//        $dateRange = $this->getDateRange($request);
//        $transactions = $transactionRepository->findByMerchantAndDateRange(
//            $merchant,
//            $dateRange['startDate'],
//            $dateRange['endDate']
//        );
//
//        $summary = $transactionRepository->getSummaryForMerchant(
//            $merchant,
//            $dateRange['startDate'],
//            $dateRange['endDate']
//        );
//
//        return $this->render('financials/commission.html.twig', [
//            'merchant' => $merchant,
//            'transactions' => $transactions,
//            'summary' => $summary,
//            'groupedTransactions' => $groupedTransactions,
////            'sales' => $groupedSales,
//            'commission' => $groupedCommission,
//            'dateRange' => $this->getDateRange($request),
//            'metricTotals' => $metricTotals // Add this line
//        ]);
//    }


    #[Route('/merchant/financials/{id}/commission', name: 'financials_commission')]
    public function commission(
        int $id,
        CommissionRepository $commissionRepository,
        MerchantRepository $merchantRepository,
        TransactionRepository $transactionRepository,
        Request $request
    ): Response {

        // Get all transactions for grouping
        $allTransactions = $transactionRepository->findAllWithMerchants();

        $merchant = $merchantRepository->find($id);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $groupedCommission = $commissionRepository->findGroupedByTypeAndProvider($id);

        $dateRange = $this->getDateRange($request);
        $groupedTransactions = [];
        $transactions = $transactionRepository->findByMerchantAndDateRange(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        $metricTotals = [
            'opening' => 0,
            'sales' => 0,
            'commission' => array_sum(array_column($groupedCommission, 'Merchant')),
            'deposits' => 0,
            'reversals' => 0,
            'closing' => 0,
        ];

        foreach ($allTransactions as $transaction) {
            $merchant = $transaction->getMerchant();
            $merchantId = $merchant->getId();

            if (!isset($groupedTransactions[$merchantId])) {

                $metricTotals['opening'] += $transaction->getOpeningBalance();
                $metricTotals['deposits'] += $transaction->getDeposits();
                $metricTotals['sales'] += $transaction->getSales();
                $metricTotals['closing'] += $transaction->getClosingBalance();
            }
        }

        return $this->render('financials/commission.html.twig', [
            'merchant' => $merchant,
            'transactions' => $transactions,
            'commission' => $groupedCommission,
            'dateRange' => $dateRange,
            'metricTotals' => $metricTotals
        ]);
    }

    #[Route('/merchant/financials/{id}/earned', name: 'financials_earned_commission')]
    public function earnedCommission(
        int $id,
        CommissionRepository $commissionRepository,
        CommissionEarnedRepository $commissionEarnedRepository,
        MerchantRepository $merchantRepository,
        TransactionRepository $transactionRepository,
        Request $request
    ): Response {

        // Get all transactions for grouping
        $allTransactions = $transactionRepository->findAllWithMerchants();

        $merchant = $merchantRepository->find($id);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $groupedCommission = $commissionRepository->findGroupedByTypeAndProvider($id);
        $groupedEarnedCommission = $commissionEarnedRepository->findGroupedByTypeAndProvider($id);
        $groupedTransactions = [];
        $metricTotals = [
            'opening' => 0,
            'sales' => 0,
            'commission' => array_sum(array_column($groupedCommission, 'Merchant')),
            'deposits' => 0,
            'reversals' => 0,
            'closing' => 0,
        ];
        $dateRange = $this->getDateRange($request);
        $transactions = $transactionRepository->findByMerchantAndDateRange(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        foreach ($allTransactions as $transaction) {
            $merchant = $transaction->getMerchant();
            $merchantId = $merchant->getId();

            if (!isset($groupedTransactions[$merchantId])) {

                $metricTotals['opening'] += $transaction->getOpeningBalance();
                $metricTotals['deposits'] += $transaction->getDeposits();
                $metricTotals['sales'] += $transaction->getSales();
                $metricTotals['closing'] += $transaction->getClosingBalance();
            }
        }


        return $this->render('financials/earned_commission.html.twig', [
            'merchant' => $merchant,
            'transactions' => $transactions,
            'commission' => $groupedCommission,
            'metricTotals' => $metricTotals,
            'dateRange' => $dateRange,
            'groupedCommission' => $groupedCommission,
            'groupedTransactions' => $groupedCommission,
            'earnedCommission' => $groupedEarnedCommission,

        ]);
    }
    private function getDateRange(Request $request): array
    {
        $session = $request->getSession();

        // Check if we have a stored range in session
        $storedRange = $session->get('date_range', 'today');

        // Get range from request or use stored one
        $range = $request->query->get('range', $storedRange);

        // Store the current range in session
        $session->set('date_range', $range);

        $today = new \DateTime();
        $startDate = clone $today;
        $endDate = clone $today;

        switch ($range) {
            case 'week':
                $startDate->modify('-7 days');
                break;
            case 'month':
                $startDate = new \DateTime('first day of this month');
                break;
            case 'today':
            default:
                // Default to today (no modification needed)
                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'display' => $this->formatDateRange($startDate, $endDate),
            'currentRange' => $range
        ];
    }

    private function formatDateRange(\DateTimeInterface $start, \DateTimeInterface $end): string
    {
        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
            return $start->format('F jS, Y');
        }

        return $start->format('F jS') . ' - ' . $end->format('F jS, Y');
    }

}
