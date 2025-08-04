<?php

namespace App\Entity\Reports\Outlet;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: "App\Repository\TransactionRepository")]
#[ORM\Table(name: "report_outlet_transactions")]
class ReportsTransaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime", name: "date_time")]
    private ?\DateTimeInterface $dateTime = null;

    #[ORM\Column(type: "string", length: 50, name: "outlet_id")]
    private ?string $outletId = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $type = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $detail = null;

    #[ORM\Column(type: "decimal", precision: 10, scale: 2)]
    private ?string $value = null;

    #[ORM\Column(type: "string", length: 20)]
    private ?string $status = null;

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

    public function getOutletId(): ?string
    {
        return $this->outletId;
    }

    public function setOutletId(string $outletId): self
    {
        $this->outletId = $outletId;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(string $detail): self
    {
        $this->detail = $detail;
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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
