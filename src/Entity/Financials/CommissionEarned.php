<?php

namespace App\Entity\Financials;

use App\Entity\Merchant;
use App\Repository\Financials\FinancialsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FinancialsRepository::class)]
#[ORM\Table(name: 'earned_commission')]
#[ORM\HasLifecycleCallbacks]
class CommissionEarned
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $commissionType;

    #[ORM\Column(type: Types::STRING, length: 100)]
    private string $provider;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $totalSales;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $merchantCommission;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private string $rate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $saleDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'commission')]
    #[ORM\JoinColumn(nullable: false)]
    private Merchant $merchant;

    // Getters and Setters
    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): self
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCommissionType(): string
    {
        return $this->commissionType;
    }

    public function setCommissionType(string $commissionType): void
    {
        $this->commissionType = $commissionType;
    }

    public function getProvider(): string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): void
    {
        $this->provider = $provider;
    }

    public function getTotalSales(): string
    {
        return $this->totalSales;
    }

    public function setTotalSales(string $totalSales): void
    {
        $this->totalSales = $totalSales;
    }

    public function getMerchantCommission(): string
    {
        return $this->merchantCommission;
    }

    public function setMerchantCommission(string $merchantCommission): void
    {
        $this->merchantCommission = $merchantCommission;
    }

    public function getRate(): string
    {
        return $this->rate;
    }

    public function setRate(string $rate): void
    {
        $this->rate = $rate;
    }

    public function getSaleDate(): ?\DateTimeInterface
    {
        return $this->saleDate;
    }

    public function setSaleDate(?\DateTimeInterface $saleDate): void
    {
        $this->saleDate = $saleDate;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        if ($this->saleDate === null) {
            $this->saleDate = new \DateTime();
        }
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
