<?php

namespace App\Controller\LimitsAndDocs;

use App\Repository\LimitsAndDocs\CreditLimitRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]
class CreditLimitController extends AbstractController
{
    #[Route('/limits/{id}', name: 'app_limits_and_docs')]
    public function creditLimit(
        int                   $id,
        MerchantRepository    $merchantRepository,
        CreditLimitRepository $creditLimitRepository
    ): Response
    {
        $merchant = $merchantRepository->find($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        $creditLimits = $creditLimitRepository->findActiveByMerchant($id);
        // Calculate total credit
        $totalCredit = array_reduce($creditLimits, function($carry, $limit) {
            return $carry + (float)$limit->getAmount();
        }, 0);

        return $this->render('limits_and_documents/credit_limit.html.twig', [
            'merchant' => $merchant,
            'credit_limits' => $creditLimits,
            'total_credit' => $totalCredit
        ]);
    }

    #[Route('/limits/{id}/edit', name: 'app_limits_and_docs_edit')]
    public function edit(int $id, Request $request): Response
    {
        // Implement edit functionality here
        return $this->redirectToRoute('app_limits_and_docs', ['id' => $id]);
    }

    #[Route('/limits/{id}/remove', name: 'app_limits_and_docs_remove')]
    public function remove(int $id, Request $request): Response
    {
        // Implement remove functionality here
        return $this->redirectToRoute('app_limits_and_docs', ['id' => $id]);
    }
}
