<?php

// src/Twig/AppExtension.php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('get_report_route', [$this, 'getReportRoute']),
        ];
    }

    public function getReportRoute(string $reportName): string
    {
        $routes = [
            'Transactions' => 'app_transactions',
            'Itemised Report' => 'app_itemised_report',
            'Sales By Category' => 'app_category_report',
            'Sales By User' => 'app_user_report',
            'Sales By Outlet' => 'app_outlet_report',
            'Sales By Date' => 'app_sales_by_date_report',
//            'Recon Report' => 'app_recon_report',
//            'Merchant Statement' => 'app_merchant_statement',
            // Add more mappings as needed
            'Group Itemised Report' => 'group_itemised_report',
            'Group Category Report' => 'group_category_report',
            'Group Sales By Date' => 'group_sales_by_date_report'

        ];

        return $routes[$reportName] ?? 'app_reports';
    }

    public function getFilters()
    {
        return [
            new TwigFilter('format_bwp', [$this, 'formatCurrency']),
        ];
    }

    public function formatCurrency($amount): string
    {
        if (null === $amount) {
            return 'N/A';
        }

        return 'P' . number_format((float) $amount, 2, '.', ',');
    }
}
