<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LimitsAndDocsController extends AbstractController
{

    #[Route('/limits', name: 'app_limits_and_docs')]
    public function show_limits_and_documents(): Response
    {
        return $this->render('limits_and_documents/credit_limit.html.twig');
    }

    #[Route('/providers', name: 'providers')]
    public function providers(): Response
    {
        return $this->render('limits_and_documents/providers.html.twig');
    }

    #[Route('/merchant/commissions', name: 'merchant_commissions')]
    public function commissions(): Response
    {
        // In a real application, you would fetch this data from a database
        $commissions = [
            'airtimeCommissions' => [
                ['service' => 'B-MOBILE', 'rate' => '9'],
                ['service' => 'MASCOM', 'rate' => '9'],
                ['service' => 'ORANGE', 'rate' => '9'],
            ],
            'electricityCommissions' => [
                ['service' => 'BPC', 'rate' => '3.5'],
            ],
            'collectionsCommissions' => [
                ['service' => 'BOTSWANA LIFE', 'rate' => '0.00'],
            ],
            'billPayments' => [
                ['service' => 'BPC', 'rate' => '0.00'],
                ['service' => 'BTC', 'rate' => '0.00'],
                ['service' => 'DSTV', 'rate' => '0.00'],
                ['service' => 'WUC', 'rate' => '0.00'],
            ],
            'agreementCounts' => [
                'view' => 4,
                'registration' => 4,
                'affidavit' => 4,
            ]
        ];

        return $this->render('limits_and_documents/merchant_agreement.html.twig', $commissions);
    }

}
