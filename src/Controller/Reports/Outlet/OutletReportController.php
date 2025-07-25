<?php

namespace App\Controller\Reports\Outlet;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class OutletReportController extends AbstractController
{
    #[Route('/outlet', name: 'app_outlet_report')]
    public function index(Request $request): Response{
        // Sample data
        $transactions = [
            [
                'id' => '3928-01',
                'outlet' => 'SCBBB',
                'sale_value' => 3420.00,
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
                return stripos($txn['detail'], $detailFilter) !== false;
            });
        }

        // Summary calculations
        $total = array_reduce($filteredTransactions, function($carry, $txn) {
            return $carry + $txn['sale_value']; // Simply sum all sale values
        }, 0);

        return $this->render('reports/outlet_reports/outlet_report.html.twig',[
            'transactions' => $filteredTransactions,
            'total' => $total,
            'detailFilter' => $detailFilter,
            'dateFilter' => $dateFilter]);
    }
}
