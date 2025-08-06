<?php

namespace App\Controller\Reports\Group;

use App\Entity\Merchant;
use App\Repository\Reports\Group\ReportGroupSaleByDateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class GroupSalesByDateController extends AbstractController
{
    #[Route('group/{id}/group_sales_by_date', name: 'group_sales_by_date_report')]
    public function index(Request $request, Merchant $merchant, ReportGroupSaleByDateRepository $reportGroupSaleByDateRepository): Response{

        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $groupDateSales = $reportGroupSaleByDateRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );
        // Summary calculations
        $total = array_reduce($groupDateSales, function($carry, $groupDateSales) {
            return $carry + (float)$groupDateSales->getSaleValue();

        }, 0);

        return $this->render('reports/group_reports/group_sales_by_date_report.html.twig',[
            'merchant' => $merchant,
            'groupDateSales' => $groupDateSales,
            'total' => $total,
            'detailFilter' => $detailFilter,
            'dateFilter' => $dateFilter]);
    }
}
