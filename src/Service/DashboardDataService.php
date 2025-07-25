<?php

// src/Service/DashboardDataService.php
namespace App\Service;

use App\Entity\Merchant;
use App\Entity\Transaction;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class DashboardDataService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getDashboardData(Merchant $merchant, Request $request): array
    {
        $dateRange = $this->getDateRange($request);

        $transactions = $this->em->getRepository(Transaction::class)
            ->findByDateAndRange(
                $merchant,
                $dateRange['startDate'],
                $dateRange['endDate']
            );

        return [
            'merchant' => $merchant,
            'transactions' => $transactions,
            'summary' => $this->calculateSummary($transactions),
            'dateRange' => $dateRange
        ];
    }

    private function getDateRange(Request $request): array
    {
        $today = new \DateTime();
        $startDate = clone $today;
        $endDate = clone $today;

        switch ($request->query->get('range')) {
            case 'week':
                $startDate = (clone $today)->modify('-7 days');
                break;
            case 'month':
                $startDate = new \DateTime('first day of this month');
                break;
        }

        return [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'display' => $this->formatDateRange($startDate, $endDate)
        ];
    }

    private function formatDateRange(\DateTimeInterface $start, \DateTimeInterface $end): string
    {
        if ($start->format('Y-m-d') === $end->format('Y-m-d')) {
            return $start->format('F jS, Y');
        }

        return $start->format('F jS') .
            ($start->format('Y') === $end->format('Y') ? ' - ' . $end->format('F jS, Y') : ' - ' . $end->format('F jS, Y'));
    }

    private function calculateSummary(array $transactions): array
    {
        return array_reduce($transactions, function($carry, $transaction) {
            $carry['opening'] += $transaction->getOpeningBalance();
            $carry['deposits'] += $transaction->getDeposits();
            $carry['sales'] += $transaction->getSales();
            $carry['closing'] += $transaction->getClosingBalance();
            return $carry;
        }, [
            'opening' => 0,
            'deposits' => 0,
            'sales' => 0,
            'closing' => 0,
        ]);
    }
}
