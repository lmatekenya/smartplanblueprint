<?php

namespace App\Controller\LimitsAndDocs;

use App\Repository\Financials\CommissionRepository;
use App\Repository\LimitsAndDocs\AgreementCommissionRepository;
use App\Repository\LimitsAndDocs\AgreementRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/merchant')]
class MerchantAgreementController extends AbstractController
{
    #[Route('/commissions/{id}', name: 'merchant_commissions')]
    public function commissions( int $id,
                                 MerchantRepository $merchantRepository,
                                 AgreementCommissionRepository $agreementCommissionRepository,
                                 AgreementRepository $agreementRepository): Response
    {
        $merchant = $merchantRepository->find($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        // Fetch actual commission data from repositories
        $airtimeCommissions = $agreementCommissionRepository->findByMerchantAndType($merchant, 'airtime');
        $electricityCommissions = $agreementCommissionRepository->findByMerchantAndType($merchant, 'electricity');
        $collectionsCommissions = $agreementCommissionRepository->findByMerchantAndType($merchant, 'collections');
        $billPayments = $agreementCommissionRepository->findByMerchantAndType($merchant, 'bill_payment');

        // Format commission data for display
        $formatCommissions = function(array $commissions): array {
            return array_map(function($commission) {
                return [
                    'service' => $commission->getServiceName(),
                    'rate' => $commission->getRate()
                ];
            }, $commissions);
        };

        // Get agreement counts
        $agreementCounts = [
            'view' => $agreementRepository->count(['merchant' => $merchant, 'type' => 'view']),
            'registration' => $agreementRepository->count(['merchant' => $merchant, 'type' => 'registration']),
            'affidavit' => $agreementRepository->count(['merchant' => $merchant, 'type' => 'affidavit'])
        ];

        return $this->render('limits_and_documents/merchant_agreement.html.twig', [
            'merchant' => $merchant,
            'airtimeCommissions' => $formatCommissions($airtimeCommissions),
            'electricityCommissions' => $formatCommissions($electricityCommissions),
            'collectionsCommissions' => $formatCommissions($collectionsCommissions),
            'billPayments' => $formatCommissions($billPayments),
            'agreementCounts' => $agreementCounts
        ]);
    }
}
