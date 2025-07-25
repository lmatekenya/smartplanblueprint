<?php

namespace App\Controller\Reports\Outlet;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TransactionsController extends AbstractController
{
    #[Route('/transactions', name: 'app_transactions')]
    public function index(Request $request): Response
    {
        // Sample data - replace with your actual data source
        $transactions = [
            [
                'date_time' => '2025-07-16 00:07:31',
                'outlet_id' => '3928.91',
                'type' => 'Sale',
                'detail' => 'Electricity - 0K001222485',
                'value' => 50.00,
                'status' => '○'
            ],
            // Add more sample transactions as needed
        ];

        // Filtering logic
        $dateFilter = $request->query->get('date');
        $detailFilter = $request->query->get('detail');

        if ($dateFilter) {
            $transactions = array_filter($transactions, function($txn) use ($dateFilter) {
                return strpos($txn['date_time'], $dateFilter) !== false;
            });
        }

        if ($detailFilter) {
            $transactions = array_filter($transactions, function($txn) use ($detailFilter) {
                return stripos($txn['detail'], $detailFilter) !== false;
            });
        }

        // Summary calculations
        $salesTotal = array_reduce($transactions, function($carry, $txn) {
            return $carry + ($txn['type'] === 'Sale' ? $txn['value'] : 0);
        }, 0);

        $failedCount = count(array_filter($transactions, function($txn) {
            return $txn['status'] === 'Failed';
        }));

        return $this->render('reports/outlet_reports/transactions.html.twig', [
            'transactions' => $transactions,
            'salesTotal' => $salesTotal,
            'failedCount' => $failedCount,
            'commission' => 0.00,
            'deposits' => 0.00,
            'reversals' => 0.00,
            'dateFilter' => $dateFilter,
            'detailFilter' => $detailFilter
        ]);
    }
}
