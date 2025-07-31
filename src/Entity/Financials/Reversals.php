<?php

namespace App\Entity\Financials;



use App\Entity\Merchant;
use App\Repository\Financials\ReversalsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReversalsRepository::class)]
#[ORM\Table(name: 'reversals')]
#[ORM\HasLifecycleCallbacks]
class Reversals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $reversalDate = null;

    #[ORM\Column(length: 255)]
    private ?string $details = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'reversals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Merchant $merchant = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $referenceNumber = null;

    #[ORM\Column(length: 20)]
    private ?string $status = 'completed';

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReversalDate(): ?\DateTimeInterface
    {
        return $this->reversalDate;
    }

    public function setReversalDate(\DateTimeInterface $reversalDate): static
    {
        $this->reversalDate = $reversalDate;
        return $this;
    }

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;
        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;
        return $this;
    }

    public function getMerchant(): ?Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(?Merchant $merchant): static
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getReferenceNumber(): ?string
    {
        return $this->referenceNumber;
    }

    public function setReferenceNumber(?string $referenceNumber): static
    {
        $this->referenceNumber = $referenceNumber;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;
        return $this;
    }
}
