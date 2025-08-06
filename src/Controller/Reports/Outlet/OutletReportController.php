<?php

namespace App\Controller\Reports\Outlet;

use App\Entity\Merchant;
use App\Repository\Reports\Outlet\ReportOutletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class OutletReportController extends AbstractController
{
    #[Route('outlet/{id}/outlets', name: 'app_outlet_report')]
    public function index(Request $request, Merchant $merchant, ReportOutletRepository $reportOutletRepository): Response{
        // Sample data
//        $transactions = [
//            [
//                'id' => '3928-01',
//                'outlet' => 'SCBBB',
//                'sale_value' => 3420.00,
//            ],
//        ];
//
//        // Get filters from request
//        $dateFilter = $request->query->get('date');
//        $detailFilter = $request->query->get('detail');
//
//        // Apply filters
//        $filteredTransactions = $transactions;
//
//        if ($dateFilter) {
//            $filteredTransactions = array_filter($filteredTransactions, function($txn) use ($dateFilter) {
//                $txnDate = date('Y-m-d', strtotime($txn['date_time']));
//                return $txnDate === $dateFilter;
//            });
//        }
//
//        if ($detailFilter) {
//            $filteredTransactions = array_filter($filteredTransactions, function($txn) use ($detailFilter) {
//                return stripos($txn['detail'], $detailFilter) !== false;
//            });
//        }

        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $dateSales = $reportOutletRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );
        // Summary calculations
        $total = array_reduce($dateSales, function($carry, $dateSales) {
            return $carry + (float)$dateSales->getSaleValue();

        }, 0);

        return $this->render('reports/outlet_reports/outlet_report.html.twig',[
            'merchant' => $merchant,
            'dateSales' => $dateSales,
            'total' => $total,
            'detailFilter' => $detailFilter,
            'dateFilter' => $dateFilter]);
    }
}
