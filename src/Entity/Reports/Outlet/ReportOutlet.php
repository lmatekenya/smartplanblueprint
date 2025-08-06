<?php

namespace App\Entity\Reports\Outlet;

use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\Reports\Outlet\ReportOutletRepository")]
#[ORM\Table(name: "report_outlets")]
class ReportOutlet
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, name: "outlet_id")]
    private ?string $outletId = null;

    #[ORM\Column(type: "string", length: 100, name: "outlet_name")]
    private ?string $outletName = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2, name: "sale_value")]
    private ?string $saleValue = null;

    #[ORM\Column(type: "datetime", name: "date_time")]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(name: "merchant_id", referencedColumnName: "id", nullable: false)]
    private ?Merchant $merchant = null;

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOutletId(): ?string
    {
        return $this->outletId;
    }

    public function setOutletId(string $outletId): self
    {
        $this->outletId = $outletId;
        return $this;
    }

    public function getOutletName(): ?string
    {
        return $this->outletName;
    }

    public function setOutletName(string $outletName): self
    {
        $this->outletName = $outletName;
        return $this;
    }

    public function getSaleValue(): ?string
    {
        return $this->saleValue;
    }

    public function setSaleValue(string $saleValue): self
    {
        $this->saleValue = $saleValue;
        return $this;
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
