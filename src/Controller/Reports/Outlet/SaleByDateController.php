<?php

namespace App\Controller\Reports\Outlet;

use App\Entity\Merchant;
use App\Repository\Reports\Outlet\ReportSaleByDateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SaleByDateController extends AbstractController
{
    #[Route('outlet/{id}/sales_by_date', name: 'app_sales_by_date_report')]
    public function index(Request $request, Merchant $merchant, ReportSaleByDateRepository $reportSaleByDateRepository): Response{
        // Sample data
        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $dateSales = $reportSaleByDateRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );
        // Summary calculations
        $total = array_reduce($dateSales, function($carry, $dateSale) {
            return $carry + (float)$dateSale->getSaleValue();

        }, 0);

        return $this->render('reports/outlet_reports/sales_by_date_report.html.twig',[
            'merchant' => $merchant,
            'dateSales' => $dateSales,
            'total' => $total,
            'detailFilter' => $detailFilter,
            'dateFilter' => $dateFilter]);
    }
}
