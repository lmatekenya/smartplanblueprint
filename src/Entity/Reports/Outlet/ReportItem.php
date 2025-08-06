<?php

namespace App\Entity\Reports\Outlet;
use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Index(name: 'idx_detail', columns: ['detail'])]
#[ORM\Index(name: 'idx_date_time', columns: ['date_time'])]
#[ORM\Entity(repositoryClass: "App\Repository\ReportItemRepository")]
#[ORM\Table(name: "report_outlet_items")]
class ReportItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_time")]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(type: "string", length: 50, name: "user_id")]
    private ?string $user_id = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $sale_type = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $transaction_detail = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $sale_value = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'merchant_id', referencedColumnName: 'id', nullable: false)]
    private ?Merchant $merchant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    public function getUser_id(): ?string
    {
        return $this->user_id;
    }
    public function setUser_id(string $user_id): self
    {
        $this->user_id = $user_id;
        return $this;
    }
    public function getSaleType(): ?string
    {
        return $this->sale_type;
    }
    public function setSaleType(string $sale_type): self
    {
        $this->sale_type = $sale_type;
        return $this;
    }
    public function getTransactionDetail(): ?string
    {
        return $this->transaction_detail;
    }
    public function setTransactionDetail(string $transaction_detail): self
    {
        $this->transaction_detail = $transaction_detail;
        return $this;
    }
    public function getSaleValue(): ?string
    {
        return $this->sale_value;
    }
    public function setSaleValue(string $sale_value): self
    {
        $this->sale_value = $sale_value;
        return $this;
    }
    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }
    public function setMerchant(?Merchant $merchant): self
    {
        $this->merchant = $merchant;
        return $this;
    }
}
