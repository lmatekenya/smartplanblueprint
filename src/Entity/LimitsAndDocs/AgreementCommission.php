<?php
//
//namespace App\Entity\LimitsAndDocs;
//
//
//use App\Entity\Merchant;
//use Doctrine\ORM\Mapping as ORM;
//
//
//class AgreementCommission
//{
//    /**
//     * @ORM\Id
//     * @ORM\GeneratedValue
//     * @ORM\Column(type="integer")
//     */
//    private $id;
//
//    /**
//     * @ORM\ManyToOne(targetEntity="Merchant", inversedBy="commissions")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $merchant;
//
//    /**
//     * @ORM\Column(type="string", length=50)
//     */
//    private $type;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $serviceName;
//
//    /**
//     * @ORM\Column(type="decimal", precision=10, scale=2)
//     */
//    private $rate;
//
//    /**
//     * @ORM\Column(type="boolean")
//     */
//    private $isActive = true;
//
//    /**
//     * @return mixed
//     */
//    public function getId()
//    {
//        return $this->id;
//    }
//
//    /**
//     * @param mixed $id
//     */
//    public function setId($id): void
//    {
//        $this->id = $id;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getMerchant()
//    {
//        return $this->merchant;
//    }
//
//    /**
//     * @param mixed $merchant
//     */
//    public function setMerchant($merchant): void
//    {
//        $this->merchant = $merchant;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getType()
//    {
//        return $this->type;
//    }
//
//    /**
//     * @param mixed $type
//     */
//    public function setType($type): void
//    {
//        $this->type = $type;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getServiceName()
//    {
//        return $this->serviceName;
//    }
//
//    /**
//     * @param mixed $serviceName
//     */
//    public function setServiceName($serviceName): void
//    {
//        $this->serviceName = $serviceName;
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getRate()
//    {
//        return $this->rate;
//    }
//
//    /**
//     * @param mixed $rate
//     */
//    public function setRate($rate): void
//    {
//        $this->rate = $rate;
//    }
//
//    public function isActive(): bool
//    {
//        return $this->isActive;
//    }
//
//    public function setIsActive(bool $isActive): void
//    {
//        $this->isActive = $isActive;
//    }
//
//    // Getters and setters...
//
//
//}


namespace App\Entity\LimitsAndDocs;

use App\Entity\Merchant;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Repository\LimitsAndDocs\AgreementCommissionRepository')]
#[ORM\Table(name: 'agreement_commissions')]
class AgreementCommission
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'commissions')]
    #[ORM\JoinColumn(name: 'merchant_id', referencedColumnName: 'id', nullable: false)]
    private Merchant $merchant;

    #[ORM\Column(type: 'string', length: 50)]
    private string $type;

    #[ORM\Column(type: 'string', length: 255)]
    private string $serviceName;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $rate;

    #[ORM\Column(type: 'boolean', options: ['default' => true])]
    private bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): self
    {
        $this->merchant = $merchant;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getServiceName(): string
    {
        return $this->serviceName;
    }

    public function setServiceName(string $serviceName): self
    {
        $this->serviceName = $serviceName;
        return $this;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): self
    {
        $this->rate = $rate;
        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }
}
