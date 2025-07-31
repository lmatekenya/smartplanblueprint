<?php

namespace App\Entity\Financials;

use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'summary')]
class Summary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Merchant $merchant = null;

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $deposits = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $commissionCredited = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $reversals = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $electricitySales = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $mascomSales = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $orangeSales = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $withdrawals = '0.00';

    #[ORM\Column(type: 'decimal', precision: 12, scale: 2)]
    private ?string $merchantCommission = '0.00';

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $periodStart;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $periodEnd;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getMerchant(): ?Merchant { return $this->merchant; }
    public function setMerchant(?Merchant $merchant): self { $this->merchant = $merchant; return $this; }
    public function getDeposits(): ?string { return $this->deposits; }
    public function setDeposits(string $deposits): self { $this->deposits = $deposits; return $this; }
    public function getCommissionCredited(): ?string { return $this->commissionCredited; }
    public function setCommissionCredited(string $commissionCredited): self { $this->commissionCredited = $commissionCredited; return $this; }
    public function getReversals(): ?string { return $this->reversals; }
    public function setReversals(string $reversals): self { $this->reversals = $reversals; return $this; }
    public function getElectricitySales(): ?string { return $this->electricitySales; }
    public function setElectricitySales(string $electricitySales): self { $this->electricitySales = $electricitySales; return $this; }
    public function getMascomSales(): ?string { return $this->mascomSales; }
    public function setMascomSales(string $mascomSales): self { $this->mascomSales = $mascomSales; return $this; }
    public function getOrangeSales(): ?string { return $this->orangeSales; }
    public function setOrangeSales(string $orangeSales): self { $this->orangeSales = $orangeSales; return $this; }
    public function getWithdrawals(): ?string { return $this->withdrawals; }
    public function setWithdrawals(string $withdrawals): self { $this->withdrawals = $withdrawals; return $this; }
    public function getMerchantCommission(): ?string { return $this->merchantCommission; }
    public function setMerchantCommission(string $merchantCommission): self { $this->merchantCommission = $merchantCommission; return $this; }
    public function getPeriodStart(): ?\DateTimeInterface { return $this->periodStart; }
    public function setPeriodStart(\DateTimeInterface $periodStart): self { $this->periodStart = $periodStart; return $this; }
    public function getPeriodEnd(): ?\DateTimeInterface { return $this->periodEnd; }
    public function setPeriodEnd(\DateTimeInterface $periodEnd): self { $this->periodEnd = $periodEnd; return $this; }

    // Calculated fields
    public function getStartingBalance(): string
    {
        return bcadd($this->deposits, bcadd($this->commissionCredited, $this->reversals, 2), 2);
    }

    public function getSalesTotal(): string
    {
        return bcadd($this->electricitySales, bcadd($this->mascomSales, $this->orangeSales, 2), 2);
    }

    public function getClosingBalance(): string
    {
        $totalIn = bcadd($this->getStartingBalance(), $this->getSalesTotal(), 2);
        return bcsub($totalIn, $this->withdrawals, 2);
    }
}
