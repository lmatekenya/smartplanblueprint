<?php

namespace App\Entity\Financials\Deposits;

use App\Entity\Merchant;
use App\Repository\Financials\DepositsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepositsRepository::class)]
#[ORM\Table(name: 'deposit_transactions')]
#[ORM\HasLifecycleCallbacks]
class Deposits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $transactionDate;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'deposits')]
    #[ORM\JoinColumn(nullable: false)]
    private Merchant $merchant;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $depositDate;

    #[ORM\Column(type: Types::DECIMAL, precision: 12, scale: 2)]
    private string $amount;

    #[ORM\Column(type: Types::STRING, length: 50)]
    private string $depositMethod;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(targetEntity: MerchantAccount::class)]
    #[ORM\JoinColumn(name: 'merchant_account_id', referencedColumnName: 'id')]
    private MerchantAccount $merchantAccount;

    #[ORM\ManyToOne(targetEntity: Bank::class)]
    #[ORM\JoinColumn(name: 'bank_id', referencedColumnName: 'id')]
    private Bank $bank;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTransactionDate(): \DateTimeInterface
    {
        return $this->transactionDate;
    }

    public function setTransactionDate(\DateTimeInterface $transactionDate): self
    {
        $this->transactionDate = $transactionDate;
        return $this;
    }

    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): self
    {
        $this->merchant = $merchant;
        return $this;
    }


    public function getMerchantAccount(): MerchantAccount
    {
        return $this->merchantAccount;
    }

    public function setMerchantAccount(MerchantAccount $merchantAccount): self
    {
        $this->merchantAccount = $merchantAccount;
        return $this;
    }

    public function getBank(): Bank
    {
        return $this->bank;
    }

    public function setBank(Bank $bank): self
    {
        $this->bank = $bank;
        return $this;
    }



    public function getDepositDate(): \DateTimeInterface
    {
        return $this->depositDate;
    }

    public function setDepositDate(\DateTimeInterface $depositDate): self
    {
        $this->depositDate = $depositDate;
        return $this;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }

    public function getDepositMethod(): string
    {
        return $this->depositMethod;
    }

    public function setDepositMethod(string $depositMethod): self
    {
        $this->depositMethod = $depositMethod;
        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
