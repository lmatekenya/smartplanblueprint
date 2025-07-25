<?php

namespace App\Controller\PortalUser;
use App\Entity\Merchant;
use App\Repository\Merchant\OutletRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/merchant')]
class OutletController extends AbstractController
{
    #[Route('/outlets/{id}', name: 'outlets')]
    public function outlets(Merchant $merchant, OutletRepository $outletRepository): Response
    {

        $outlets = $outletRepository->findByMerchant($merchant->getId());

        return $this->render('merchant/outlet.html.twig', [
            'merchant' => $merchant,
            'outlets' => $outlets
        ]);
    }

    #[Route('/outlet_users/{id}', name: 'outlet_users')]
    public function outletUsers(){
        return $this->render('merchant/outlet.html.twig');
    }
}
