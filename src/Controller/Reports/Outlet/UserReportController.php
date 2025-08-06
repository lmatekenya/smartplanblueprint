<?php

namespace App\Controller\Reports\Outlet;

use App\Entity\Merchant;
use App\Entity\Reports\Outlet\ReportUser;
use App\Repository\Reports\Outlet\ReportUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/merchant')]
class UserReportController extends AbstractController
{
    #[Route('outlet/{id}/user', name: 'app_user_report')]
    public function users(Request $request, Merchant $merchant, ReportUserRepository $reportUserRepository): Response{

        $dateFilter = $request->query->get('date');
        $startDateFilter = $request->query->get('startDate');
        $endDateFilter = $request->query->get('endDate');
        $detailFilter = $request->query->get('detail');

        $users = $reportUserRepository->findByFilters(
            $merchant->getId(),
            $dateFilter,
            $detailFilter
        );
        // Summary calculations
        $total = array_reduce($users, function($carry, $users) {
            return $carry + (float)$users->getSaleValue();
        }, 0);

        return $this->render('reports/outlet_reports/user_report.html.twig',[
            'merchant' => $merchant,
            'users' => $users,
            'total' => $total,
            'detailFilter' => $detailFilter,
            'dateFilter' => $dateFilter]);
    }
}
