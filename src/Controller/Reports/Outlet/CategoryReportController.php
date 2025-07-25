<?php

namespace App\Controller\Reports\Outlet;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class CategoryReportController extends AbstractController
{
    #[Route('/category', name: 'app_category_report')]
    public function index(Request $request): Response{
        // Sample data
        $transactions = [
            [
                'date_time' => '2025-07-16 00:07:31',
                'sale_value' => 200.00,
            ],
            [
                'date_time' => '2025-07-12 00:07:31',
                'sale_value' => 50.00,
            ],
            [
                'date_time' => '2025-07-12 00:08:45',
                'sale_value' => 150.00,
            ],
        ];

        // Get filters from request
        $dateFilter = $request->query->get('date');

        // Apply filters
        $filteredTransactions = $transactions;

        if ($dateFilter) {
            $filteredTransactions = array_filter($filteredTransactions, function($txn) use ($dateFilter) {
                $txnDate = date('Y-m-d', strtotime($txn['date_time']));
                return $txnDate === $dateFilter;
            });
        }

        // Summary calculations
        $total = array_reduce($filteredTransactions, function($carry, $txn) {
            return $carry + $txn['sale_value']; // Simply sum all sale values
        }, 0);

        return $this->render('reports/outlet_reports/category_report.html.twig',[
            'transactions' => $filteredTransactions,
            'total' => $total,
            'dateFilter' => $dateFilter]);
    }
}
