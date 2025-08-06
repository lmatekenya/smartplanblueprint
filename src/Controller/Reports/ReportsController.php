<?php

namespace App\Controller\Reports;

use App\Controller\Dto\Report;
use App\Entity\Merchant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportsController extends AbstractController
{
    #[Route('/reports/{id}', name: 'app_reports')]
    public function reports(int $id, Merchant $merchant): Response
    {
        $reportCategories = [
                'OUTLET' => [
//                    'Transactions' => [
//                        'name' => 'Transactions',
//                        'route' => 'app_transactions',
//                        'params' => ['outletId' => $outletId]
//                    ],
//                    'Itemised Report' => [
//                        'name' => 'Itemised Report',
//                        'route' => 'app_itemised_report',
//                        'params' => ['outletId' => $outletId]
//                    ],
                    new Report('Transactions', 'app_transactions', ['outletId' => $id]),
                    new Report('Itemised Report', 'app_itemised_report', ['outletId' => $id]),
                    new Report('Category Report', 'app_category_report', ['outletId' => $id]),
                    new Report('User Report', 'app_user_report', ['outletId' => $id]),
                    new Report('Outlet Report', 'app_outlet_report', ['outletId' => $id]),
                    new Report('Sales By Date', 'app_sales_by_date_report', ['outletId' => $id]),
//                    'Sales By Category' => [
//                        'name' => 'Sales By Category',
//                        'route' => 'app_category_report',
//                        'params' => ['outletId' => $outletId]
//                    ],
//                    'Sales By User' => [
//                        'name' => 'Sales By User',
//                        'route' => 'app_user_report',
//                        'params' => ['outletId' => $outletId]
//                    ],
//                    'Sales By Outlet' => [
//                        'name' => 'Sales By Outlet',
//                        'route' => 'app_outlet_report',
//                        'params' => ['outletId' => $outletId]
//                    ],
//                    'Sales By Date' => [
//                        'name' => 'Sales By Date',
//                        'route' => 'app_sales_by_date_report',
//                        'params' => ['outletId' => $outletId]
//                    ],


                    'Recon Report' => [
                        'name' => 'Recon Report',
//                        'route' => 'app_recon_report',
                        'params' => ['outletId' => $id]
                    ],
                    'Transactions by Date Range' => [
                        'name' => 'Transactions by Date Range',
//                        'route' => 'app_transactions_by_date_range',
                        'params' => ['outletId' => $id]
                    ],
                    'Merchant Statement' => [
                        'name' => 'Merchant Statement',
//                        'route' => 'app_merchant_statement',
                        'params' => ['outletId' => $id]
                    ]


//                'Sales By User',
//                'Sales By Outlet',
//                'Sales By Date',
//                'Recon Report',
//                'Transactions by Date Range',
//                'Merchant Statement'
                ],
            'GROUP' => [
                'Group Itemised Report' => [
                    'name' => 'Group Itemised Report',
                    'route' => 'group_itemised_report',
                    'params' => ['outletId' => $id],
                ],
                'Group Category Report' => [
                    'name' => 'Group Category Report',
                    'route' => 'group_category_report',
                    'params' => ['outletId' => $id],
                ],
                'Group Sales By Date' => [
                    'name' => 'Group Sales By Date',
                    'route' => 'group_sales_by_date_report',
                    'params' => ['outletId' => $id],
                ],

//                'Group Itemised Report',
//                'Group Category Report',
//                'Group Sales By Date',

            ]

        ];

        return $this->render('reports/reports_dashboard.html.twig', [
            'merchant' => $merchant,
            'reportCategories' => $reportCategories,
            'outletId' => $id
        ]);
    }
}
