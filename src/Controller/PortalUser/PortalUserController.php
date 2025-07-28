<?php

namespace App\Controller\PortalUser;

use App\Entity\Merchant;
use App\Repository\Merchant\PortalUserRepository;
use App\Repository\MerchantRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/merchant')]
class PortalUserController extends AbstractController
{
    #[Route('/portal_users/{id}', name: 'portal_users')]
    public function portalUsers(
        Merchant $merchant,
        PortalUserRepository $portalUserRepository
    ): Response
    {
        $portalUsers = $portalUserRepository->findByMerchantWithDetails($merchant->getId());

        return $this->render('merchant/portal_users.html.twig', [
            'merchant' => $merchant,
            'portalUsers' => $portalUsers
        ]);
    }
}
