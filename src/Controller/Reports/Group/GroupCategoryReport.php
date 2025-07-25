<?php

namespace App\Controller\Reports\Group;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class GroupCategoryReport extends AbstractController
{
    #[Route('/groupCategory', name: 'group_category_report')]
    public function index(Request $request): Response{
        // Sample data
        $transactions = [
            [
                'category' => 'Airtime',
                'provider' => 'Orange Pinless',
                'sale_value' => 200.00,
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

        return $this->render('reports/group_reports/group_category_report.html.twig',[
            'transactions' => $filteredTransactions,
            'total' => $total,
            'dateFilter' => $dateFilter]);
    }
}
