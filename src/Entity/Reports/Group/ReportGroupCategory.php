<?php

namespace App\Entity\Reports\Group;
use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Index(name: 'idx_detail', columns: ['detail'])]
#[ORM\Index(name: 'idx_date_time', columns: ['date_time'])]
#[ORM\Entity(repositoryClass: "App\Repository\ReportGroupCategoryRepository")]
#[ORM\Table(name: "report_group_category")]
class ReportGroupCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_time")]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(type: "string", length: 50, name: "category")]
    private ?string $category = null;

    #[ORM\Column(type: "string", length: 50, name: "provider")]
    private ?string $provider = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $saleValue = null;

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
    public function getCategory(): ?string
    {
        return $this->category;
    }
    public function setCategory(string $category): self
    {
        $this->category = $category;
        return $this;
    }
    public function getProvider(): ?string
    {
        return $this->provider;
    }
    public function setProvider(string $provider): self
    {
        $this->provider = $provider;
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
