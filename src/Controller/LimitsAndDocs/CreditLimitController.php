<?php

namespace App\Controller\LimitsAndDocs;

use App\Entity\LimitsAndDocs\CreditLimit;
use App\Entity\Merchant;
use App\Repository\LimitsAndDocs\CreditLimitRepository;
use App\Repository\MerchantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        CreditLimitRepository $creditLimitRepository,
        EntityManagerInterface $em
    ): Response
    {
        $merchant = $merchantRepository->find($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        // Check for expired limits
        $creditLimits = $merchant->getCreditLimits();
        $now = new \DateTime();
        $todayEnd = (new \DateTime())->setTime(23, 59, 59);

        foreach ($creditLimits as $limit) {
            if ($limit->getExpiryDate() && $limit->getExpiryDate() <= $todayEnd) {
                $limit->setStatus('Expired');
            }
        }

        $em->flush();

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
    public function edit(int $id, Request $request, Merchant $merchant, EntityManagerInterface $entityManager): Response
    {
//        // Implement edit functionality here
//        return $this->redirectToRoute('app_limits_and_docs', ['id' => $id]);


        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $limitId = $request->request->get('limitId');
        $creditLimit = $entityManager->getRepository(CreditLimit::class)->find($limitId);

        if (!$creditLimit) {
            $this->addFlash('error', 'Credit limit not found');
            return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchant->getId()]);
        }

        // Validate and update fields
        $amount = $request->request->get('amount');
        $expiryDate = $request->request->get('expiryDate');
        $status = $request->request->get('status');

        if (!is_numeric($amount) || $amount <= 0) {
            $this->addFlash('error', 'Invalid amount');
            return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchant->getId()]);
        }

        try {
            $expiryDateObj = new \DateTime($expiryDate);
        } catch (\Exception $e) {
            $this->addFlash('error', 'Invalid expiry date');
            return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchant->getId()]);
        }

        $creditLimit->setAmount($amount);
        $creditLimit->setExpiryDate($expiryDateObj);

        if ($status) {
            $creditLimit->setStatus($status);
        }

        $entityManager->flush();

        $this->addFlash('success', 'Credit limit updated successfully');
        return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchant->getId()]);
    }

    #[Route('/{merchantId}/limits/{limitId}/remove ', name: 'app_limits_and_docs_remove')]
    public function remove( int $limitId,
                            int $merchantId,
                            Request $request,
                            EntityManagerInterface $entityManager
    ): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Find merchant first to ensure it exists
        $merchant = $entityManager->getRepository(Merchant::class)->find($merchantId);
        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        // Find credit limit with merchant relationship check
        $creditLimit = $entityManager->getRepository(CreditLimit::class)->findOneBy([
            'id' => $limitId,
            'merchant' => $merchant
        ]);

        if (!$creditLimit) {
            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => false, 'message' => 'Credit limit not found for this merchant'], 404);
            }
            throw $this->createNotFoundException('Credit limit not found for this merchant');
        }

        // CSRF token validation
        $submittedToken = $request->request->get('_token');
        if (!$this->isCsrfTokenValid('delete_credit_limit', $submittedToken)) {
            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => false, 'message' => 'Invalid CSRF token'], 400);
            }
            throw $this->createAccessDeniedException('Invalid CSRF token');
        }

        try {
            $entityManager->remove($creditLimit);
            $entityManager->flush();

            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => true]);
            }

            $this->addFlash('success', 'Credit limit deleted successfully');
            return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchantId]);
        } catch (\Exception $e) {
            if ($request->isXmlHttpRequest()) {
                return $this->json(['success' => false, 'message' => $e->getMessage()], 500);
            }

            $this->addFlash('error', 'Error deleting credit limit: ' . $e->getMessage());
            return $this->redirectToRoute('app_limits_and_docs', ['id' => $merchantId]);
        }
    }

    #[Route('/limits/{id}/update_status', name: 'credit_limit_update_status', methods: ['POST'])]
    public function updateCreditLimitStatus(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $creditLimit = $em->getRepository(CreditLimit::class)->find($data['id']);
        if (!$creditLimit) {
            return $this->json(['success' => false, 'message' => 'Credit limit not found']);
        }

        $creditLimit->setStatus('Expired');
        $em->flush();

        return $this->json(['success' => true]);
    }
}
