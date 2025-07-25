<?php

// src/Controller/MerchantController.php
namespace App\Controller;

use App\Entity\Merchant;
use App\Entity\Merchant\MerchantDetails;
use App\Entity\Merchant\PortalUserDetails;
use App\Repository\Merchant\PortalUserRepository;
use App\Repository\MerchantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/merchant')]  // Merchant route prefix
class MerchantController extends AbstractController
{
    #[Route('/details/{id}', name: 'merchant_details')]
    public function details(int $id, MerchantRepository $merchantRepository): Response
    {
        $merchant = $merchantRepository->findWithDetails($id);

        if (!$merchant) {
            throw $this->createNotFoundException('Merchant not found');
        }

        if (!$merchant->getDetails()) {
            $merchant->setDetails(new MerchantDetails());
        }

        return $this->render('merchant/show.html.twig', [
            'merchant' => $merchant,
            'details' => $merchant->getDetails()
        ]);
    }

}
