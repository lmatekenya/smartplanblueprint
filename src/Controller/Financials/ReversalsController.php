<?php

namespace App\Controller\Financials;

use App\Entity\Financials\Reversals;
use App\Entity\Merchant;
use App\Repository\MerchantRepository;
use App\Repository\TransactionRepository;
use App\Service\DateRangeHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]
class ReversalsController extends AbstractController
{
//    #[Route('/financials/{id}/reversals', name: 'financials_reversals')]
//    public function reversals(): Response
//    {
//
//        return $this->render('financials/reversals.html.twig');
//    }


    #[Route('/financials/{id}/reversals', name: 'financials_reversals')]
    public function reversals(
        Merchant $merchant,
        Request $request,
        EntityManagerInterface $em,
        DateRangeHelper $dateRangeHelper
    ): Response {
        $range = $request->query->get('range', 'week');
        $dateRange = $dateRangeHelper->getDateRange($range);

        $reversalRepository = $em->getRepository(Reversals::class);
        $reversals = $reversalRepository->findByMerchantAndDateRange(
            $merchant,
            $dateRange->getStartDate(),
            $dateRange->getEndDate()
        );

        $totalReversals = $reversalRepository->getTotalReversalsForMerchant(
            $merchant,
            $dateRange->getStartDate(),
            $dateRange->getEndDate()
        );

        // Get other metric totals
        $metricTotals = [
            'opening' => 0,
            'sales' => 0, // Implement with your Sales repository
            'commission' => 0, // Implement with your Commission repository
            'deposits' => 0, // Implement with your Deposit repository
            'reversals' => $totalReversals,
            'closing' => 0,
        ];

        return $this->render('financials/reversals.html.twig', [
            'merchant' => $merchant,
            'reversals' => $reversals,
            'dateRange' => $dateRange,
            'metricTotals' => $metricTotals,
        ]);
    }

//    #[Route('/financials/{id}/reversals', name: 'financials_reversals')]
//    public function reversals(  int $id,
//        Merchant $merchant,
//        Request $request,
//        EntityManagerInterface $em,
//        DateRangeHelper $dateRangeHelper,
//    MerchantRepository $merchantRepository,
//    TransactionRepository $transactionRepository,
//
//    ): Response {
////        $range = $request->query->get('range', 'week');
////        $dateRange = $dateRangeHelper->getDateRange($range);
//
//        $merchant = $merchantRepository->find($id);
//        if (!$merchant) {
//            throw $this->createNotFoundException('Merchant not found');
//        }
//
//        // Get all transactions for grouping
//        $allTransactions = $transactionRepository->findAllWithMerchants();
//
//        $reversalRepository = $em->getRepository( Reversals::class);
//        $reversals = $reversalRepository->findByMerchantAndDateRange($merchant->getId());
////
////        $totalReversals = $reversalRepository->getTotalReversalsForMerchant(
////            $merchant,
////            $dateRange->getStartDate(),
////            $dateRange->getEndDate()
////        );
//
//        $formattedReversals = array_map(function($reversals) {
//            return [
//                'reversalDate' => $reversals->getReversalDate(),
//                'reference' => $reversals->getReference(),
//                'description' => $reversals->getDescription(),
//                'amount' => $reversals->getAmount(),
//                'status' => $reversals->getStatus()
//            ];
//        }, $reversals);;
//
//
//        // Get other metric totals (you would implement these similarly)
//        $metricTotals = [
//            'opening' => 0,
//            'sales' => 0, // Implement with your Sales repository
//            'commission' => 0, // Implement with your Commission repository
//            'deposits' => 0, // Implement with your Deposit repository
//            'reversals' => $formattedReversals,
//            'closing' => 0,
//        ];
//
//        return $this->render('financials/reversals.html.twig', [
//            'merchant' => $merchant,
//            'reversals' => $reversals,
//            'dateRange' => $dateRange,
//            'metricTotals' => $metricTotals,
//        ]);
//    }
}
