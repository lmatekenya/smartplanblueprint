<?php

namespace App\Controller\Financials;

use App\Repository\TransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\Financials\FinancialsRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]
class SalesController extends AbstractController
{
    #[Route('/financials/{id}/sales', name: 'app_financials')]
    public function sales(int $id, FinancialsRepository $saleRepository,
    MerchantRepository $merchantRepository,
    Request $request, TransactionRepository $transactionRepository
    ): Response
    {
//
//        // Get all transactions for grouping
//        $allTransactions = $transactionRepository->findAllWithMerchants();
//
//        // Group transactions by merchant ID and calculate totals
//        $groupedTransactions = [];
//        $metricTotals = [
//            'opening' => 0,
//            'deposits' => 0,
//            'sales' => 0,
//            'commission' => 0,
//            'reversals' => 0,
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
//
//        $merchant = $merchantRepository->find($id);
//        if (!$merchant) {
//            throw $this->createNotFoundException('Merchant not found');
//        }
//
////        // Get sales for this merchant
////        $sales = $saleRepository->findBy(['merchant' => $id], ['saleDate' => 'DESC']);
//
//        $groupedSales = $saleRepository->findGroupedByProvider($id);
//        $dateRange = $this->getDateRange($request);
//
//        return $this->render('financials/financials.html.twig', [
//            'merchant' => $merchant,
//            'sales' => $groupedSales,
//            'dateRange' => $dateRange,
//        ]);


        // Get all transactions for grouping
        $allTransactions = $transactionRepository->findAllWithMerchants();


        $merchant = $merchantRepository->find($id);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $groupedSales = $saleRepository->findGroupedByProvider($id);
        $groupedTransactions = [];
        // Calculate metric totals
        $metricTotals = [
            'opening' => 0,
            'sales' => array_sum(array_column($groupedSales, 'total')),
            'deposits' => 0, // You'll need to calculate this from your data
            'commission' => 0, // Calculate if you have commission data
            'reversals' => 0, // Calculate if you have reversal data
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

        $dateRange = $this->getDateRange($request);
        $transactions = $transactionRepository->findByMerchantAndDateRange(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        $summary = $transactionRepository->getSummaryForMerchant(
            $merchant,
            $dateRange['startDate'],
            $dateRange['endDate']
        );

        return $this->render('financials/financials.html.twig', [
            'merchant' => $merchant,
            'transactions' => $transactions,
            'summary' => $summary,
            'groupedTransactions' => $groupedTransactions,
            'sales' => $groupedSales,
            'dateRange' => $this->getDateRange($request),
            'metricTotals' => $metricTotals // Add this line
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
