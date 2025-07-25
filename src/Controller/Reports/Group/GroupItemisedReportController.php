<?php

namespace App\Controller\Reports\Group;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupItemisedReportController extends AbstractController
{
    #[Route('/groupItemised', name: 'group_itemised_report')]
    public function index(Request $request): Response
    {
        // Sample data
        $transactions = [
            [
                'date_time' => '2025-07-16 00:07:31',
                'outlet_id' => 'Smart Plan Blueprint-01',
                'sale_type' => 'Electricity',
                'transaction_detail' => '0K001222485',
                'sale_value' => 200.00,
            ],
            [
                'date_time' => '2025-07-12 00:07:31',
                'outlet_id' => 'Standard Chartered',
                'sale_type' => 'Airtime',
                'transaction_detail' => 'Orange Pinless',
                'sale_value' => 50.00,
            ],
        ];

        // Get filters from request
        $dateFilter = $request->query->get('date');
        $detailFilter = $request->query->get('detail');

        // Apply filters
        $filteredTransactions = $transactions;

        if ($dateFilter) {
            $filteredTransactions = array_filter($filteredTransactions, function($txn) use ($dateFilter) {
                $txnDate = date('Y-m-d', strtotime($txn['date_time']));
                return $txnDate === $dateFilter;
            });
        }

        if ($detailFilter) {
            $filteredTransactions = array_filter($filteredTransactions, function($txn) use ($detailFilter) {
                // Search in both sale_type and transaction_detail (case insensitive)
                return (stripos($txn['sale_type'], $detailFilter) !== false) ||
                    (stripos($txn['transaction_detail'], $detailFilter) !== false);
            });
        }

        return $this->render('reports/group_reports/itemised-report.html.twig', [
            'transactions' => $filteredTransactions,
            'dateFilter' => $dateFilter,
            'detailFilter' => $detailFilter
        ]);
    }
}
