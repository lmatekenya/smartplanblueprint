<?php

namespace App\Entity\Reports\Group;
use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: "App\Repository\Reports\Outlet\ReportGroupSaleByDateRepository")]
#[ORM\Table(name: "report_group_sale_by_date")]
class ReportGroupSaleByDate
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

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
