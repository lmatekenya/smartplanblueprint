<?php

namespace App\Entity\Reports\Outlet;

use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Index(name: 'idx_detail', columns: ['detail'])]
#[ORM\Index(name: 'idx_date_time', columns: ['date_time'])]
#[ORM\Entity(repositoryClass: "App\Repository\ReportCategoryRepository")]
#[ORM\Table(name: "report_outlet_category")]
class ReportCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_time")]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $value = null;

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
    public function getValue(): ?string
    {
        return $this->value;
    }
    public function setValue(string $value): self
    {
        $this->value = $value;
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
