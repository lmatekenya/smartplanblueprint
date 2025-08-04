<?php
//// src/Entity/ReportsTransaction.php
//namespace App\Entity;
//
//use Doctrine\ORM\Mapping as ORM;
//
//#[ORM\Entity(repositoryClass: "App\Repository\TransactionRepository")]
//class Transaction
//{
//    #[ORM\Id]
//    #[ORM\GeneratedValue]
//    #[ORM\Column(type: 'integer')]
//    private ?int $id = null;
//
//    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'transaction')]
//    #[ORM\JoinColumn(nullable: false)]
//    private ?Merchant $merchant = null;
//
//    #[ORM\Column(type: 'date')]
//    private ?\DateTimeInterface $date = null;
//
//    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
//    private float $openingBalance = 0;
//
//    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
//    private float $deposits = 0;
//
//    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
//    private float $sales = 0;
//
//    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
//    private float $closingBalance = 0;
//
//    // Type-hinted getters and setters...
//    public function getId(): ?int { return $this->id; }
//
//    public function getMerchant(): ?Merchant
//    {
//        return $this->merchant;
//    }
//
//    public function setMerchant(?Merchant $merchant): void
//    {
//        $this->merchant = $merchant;
//    }
//
//
//    public function getDate(): ?\DateTimeInterface
//    {
//        return $this->date;
//    }
//
//    public function setDate(?\DateTimeInterface $date): void
//    {
//        $this->date = $date;
//    }
//
//    public function getOpeningBalance(): float
//    {
//        return $this->openingBalance;
//    }
//
//    public function setOpeningBalance(float $openingBalance): void
//    {
//        $this->openingBalance = $openingBalance;
//    }
//
//    public function getDeposits(): float
//    {
//        return $this->deposits;
//    }
//
//    public function setDeposits(float $deposits): void
//    {
//        $this->deposits = $deposits;
//    }
//
//    public function getSales(): float
//    {
//        return $this->sales;
//    }
//
//    public function setSales(float $sales): void
//    {
//        $this->sales = $sales;
//    }
//
//    public function getClosingBalance(): float
//    {
//        return $this->closingBalance;
//    }
//
//    public function setClosingBalance(float $closingBalance): void
//    {
//        $this->closingBalance = $closingBalance;
//    }
//
//    // ... other getters/setters
//}

// src/Entity/ReportsTransaction.php
namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'transaction')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Merchant $merchant = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $openingBalance = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $deposits = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $sales = 0;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $closingBalance = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): void
    {
        $this->merchant = $merchant;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getOpeningBalance(): float
    {
        return $this->openingBalance;
    }

    public function setOpeningBalance(float $openingBalance): void
    {
        $this->openingBalance = $openingBalance;
    }

    public function getDeposits(): float
    {
        return $this->deposits;
    }

    public function setDeposits(float $deposits): void
    {
        $this->deposits = $deposits;
    }

    public function getSales(): float
    {
        return $this->sales;
    }

    public function setSales(float $sales): void
    {
        $this->sales = $sales;
    }

    public function getClosingBalance(): float
    {
        return $this->closingBalance;
    }

    public function setClosingBalance(float $closingBalance): void
    {
        $this->closingBalance = $closingBalance;
    }

    // ... getters and setters ...

}
