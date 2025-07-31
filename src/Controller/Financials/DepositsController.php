<?php

namespace App\Controller\Financials;

use App\Entity\Financials\Deposits\Deposits;
use App\Repository\Financials\DepositsRepository;
use App\Repository\MerchantRepository;
use App\Repository\TransactionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/merchant')]
class DepositsController extends AbstractController
{
    #[Route('/financials/{id}/deposits', name: 'financials_deposits')]
    public function deposits(
        int $id,
        DepositsRepository $depositRepository,
        MerchantRepository $merchantRepository,
        Request $request,
        TransactionRepository $transactionRepository
    ): Response {

        $merchant = $merchantRepository->find($id);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        // Get all transactions for grouping
        $allTransactions = $transactionRepository->findAllWithMerchants();

        // Get deposits for the current merchant
        $deposits = $depositRepository->findByMerchant($merchant->getId());

        // Format deposits for display
        $formattedDeposits = array_map(function($deposit) {
            return [
                'transactionDate' => $deposit->getTransactionDate(),
                'merchantAccount' => $deposit->getMerchantAccount()->getAccountName(),
                'bank' => $deposit->getBank()->getName(),
                'depositDate' => $deposit->getDepositDate(),
                'amount' => $deposit->getAmount(),
                'depositMethod' => $deposit->getDepositMethod()
            ];
        }, $deposits);

        // Calculate metric totals
        $metricTotals = [
            'opening' => 0,
            'sales' => 0,
            'deposits' => array_sum(array_column($formattedDeposits, 'Amount')),
            'commission' => 0,
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

        $dateRange = $this->getDateRange($request);

        return $this->render('financials/deposits.html.twig', [
            'merchant' => $merchant,
            'deposits' => $formattedDeposits,
            'dateRange' => $dateRange,
            'metricTotals' => $metricTotals
        ]);
    }

    private function getDateRange(Request $request): array
    {
        $range = $request->query->get('range', 'today');
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
