<?php

namespace App\Controller\Reports\Group;

use App\Entity\Merchant;
use App\Repository\Reports\Group\ReportGroupItemisedRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupItemisedReportController extends AbstractController
{
    #[Route('group/{id}/groupItemised', name: 'group_itemised_report')]
    public function index(Request $request, Merchant $merchant, ReportGroupItemisedRepository $reportGroupItemisedRepository): Response
    {
        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $groupItem = $reportGroupItemisedRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );

        return $this->render('reports/group_reports/itemised-report.html.twig', [
            'merchant' => $merchant,
            'groupItems' => $groupItem,
            'dateFilter' => $dateFilter,
            'detailFilter' => $detailFilter
        ]);
    }
}
