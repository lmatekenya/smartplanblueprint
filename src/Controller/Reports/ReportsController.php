<?php

namespace App\Controller\Reports;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportsController extends AbstractController
{
    #[Route('/reports', name: 'app_reports')]
    public function reports(): Response
    {
        $reportCategories = [
            'OUTLET' => [
                'Transactions',
                'Itemised Report',
                'Sales By Category',
                'Sales By User',
                'Sales By Outlet',
                'Sales By Date',
                'Recon Report',
                'Transactions by Date Range',
                'Merchant Statement'
            ],
            'GROUP' => [
                'Group Itemised Report',
                'Group Category Report',
                'Group Sales By Date',
            ]
        ];

        return $this->render('reports/reports_dashboard.html.twig', [
            'reportCategories' => $reportCategories,
        ]);
    }
}
