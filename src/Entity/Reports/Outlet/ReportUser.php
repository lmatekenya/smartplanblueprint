<?php

namespace App\Entity\Reports\Outlet;
use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Index(name: 'idx_detail', columns: ['detail'])]
#[ORM\Index(name: 'idx_date_time', columns: ['date_time'])]
#[ORM\Entity(repositoryClass: "App\Repository\ReportUserRepository")]
#[ORM\Table(name: "report_outlet_users")]
class ReportUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 50, name: "user_id")]
    private ?string $userId = null;

    #[ORM\Column(type: "string", length: 50)]
    private ?string $username = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $sale_value = null;

    #[ORM\Column(type: "datetime", name: "date_time", nullable: true)]
    private ?\DateTimeInterface $dateTime = null;
    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(name: 'merchant_id', referencedColumnName: 'id', nullable: false)]
    private ?Merchant $merchant = null;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUserId(): ?string
    {
        return $this->userId;
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

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;
        return $this;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }
    public function setUsername(string $username): self
    {
        $this->username = $username;
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
