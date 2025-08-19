<?php

namespace App\Entity\LimitsAndDocs;
// src/Entity/LimitsAndDocs/AgreementDocument.php
namespace App\Entity\LimitsAndDocs;

use App\Entity\Merchant;
use App\Repository\LimitsAndDocs\AgreementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AgreementRepository::class)]
#[ORM\Table(name: 'document_upload')]
class AgreementDocument
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $merchant;

    #[ORM\Column(type: 'string', length: 50)]
    private $type;

    #[ORM\Column(type: 'string', length: 255)]
    private $filename;

    #[ORM\Column(type: 'string', length: 255)]
    private $originalFilename;

    #[ORM\Column(type: 'string', length: 100)]
    private $mimeType;

    #[ORM\Column(type: 'integer')]
    private $size;

    #[ORM\Column(type: 'datetime_immutable')]
    private $uploadedAt;

    // Add getters and setters for all properties
    // ...

    public function getId(): ?int
    {
        return $this->id;
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
    public function getType(): ?string
    {
        return $this->type;
    }
    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }
    public function getFilename(): ?string
    {
        return $this->filename;
    }
    public function setFilename(string $filename): self
    {
        $this->filename = $filename;
        return $this;
    }
    public function getOriginalFilename(): ?string
    {
        return $this->originalFilename;
    }
    public function setOriginalFilename(string $originalFilename): self
    {
        $this->originalFilename = $originalFilename;
        return $this;
    }
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }
    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;
        return $this;
    }
    public function getSize(): ?int
    {
        return $this->size;
    }
    public function setSize(int $size): self
    {
        $this->size = $size;
        return $this;
    }
    public function getUploadedAt(): ?\DateTimeImmutable
    {
        return $this->uploadedAt;
    }
    public function setUploadedAt(\DateTimeImmutable $uploadedAt): self
    {
        $this->uploadedAt = $uploadedAt;
        return $this;
    }

}
