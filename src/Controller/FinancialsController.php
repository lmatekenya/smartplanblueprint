<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FinancialsController extends AbstractController
{
    #[Route('/financials', name: 'app_financials')]
    public function show_financials(): Response
    {
        return $this->render('financials/financials.html.twig');
    }

    #[Route('/commission', name: 'financials_commission')]
    public function commission(): Response
    {
        return $this->render('financials/commission.html.twig');
    }

    #[Route('/earned_commission', name: 'financials_earned_commission')]
    public function earned_commission(): Response
    {
        return $this->render('financials/earned_commission.html.twig');
    }

    #[Route('/deposits', name: 'financials_deposits')]
    public function deposits(): Response
    {
        return $this->render('financials/deposits.html.twig');
    }

    #[Route('/reversals', name: 'financials_reversals')]
    public function reversals(): Response
    {
        return $this->render('financials/reversals.html.twig');
    }

    #[Route('/withdrawals', name: 'financials_withdrawals')]
    public function withdrawals(): Response
    {
        return $this->render('financials/withdrawals.html.twig');
    }

    #[Route('/transfers', name: 'financials_transfers')]
    public function transfers(): Response
    {
        return $this->render('financials/transfers.html.twig');
    }

    #[Route('/summary', name: 'financials_summary')]
    public function summary(): Response
    {
        return $this->render('financials/summary.html.twig');
    }

}

