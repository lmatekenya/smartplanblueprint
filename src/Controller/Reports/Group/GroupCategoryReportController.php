<?php

namespace App\Controller\Reports\Group;

use App\Entity\Merchant;
use App\Repository\Reports\Group\ReportGroupCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupCategoryReportController extends AbstractController
{
    #[Route('group/{id}/groupCategory', name: 'group_category_report')]
    public function index(Request $request, Merchant $merchant, ReportGroupCategoryRepository $reportGroupCategoryRepository): Response{

        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $groupCategory = $reportGroupCategoryRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );

        $total = array_reduce($groupCategory, function($carry, $groupCategory) {
            return $carry + (float)$groupCategory->getValue();
        }, 0);

        return $this->render('reports/group_reports/group_category_report.html.twig',[
            'merchant' => $merchant,
            'groupCategories' => $groupCategory,
            'total' => $total,
            'dateFilter' => $dateFilter]);
    }
}
