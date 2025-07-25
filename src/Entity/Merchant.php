<?php

// src/Entity/Merchant.php
namespace App\Entity;


use App\Entity\Merchant\MerchantDetails;
use App\Entity\Merchant\OutletDetails;
use App\Entity\Merchant\PortalUserDetails;
use App\Repository\MerchantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: MerchantRepository::class)]
class Merchant
{

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'merchant', cascade: ['persist', 'remove'])]
    #[ORM\OrderBy( ['date' => 'DESC' ])]
    #[ORM\JoinColumn(nullable: false)]
    private $transaction;

    #[ORM\OneToOne(targetEntity: MerchantDetails::class, mappedBy: 'merchant', cascade: ['persist', 'remove'])]
    private $details;

    #[ORM\OneToMany(targetEntity: PortalUserDetails::class, mappedBy: 'merchant', cascade: ['persist', 'remove'])]
    private Collection $portalUsers;

    #[ORM\OneToMany(targetEntity: OutletDetails::class, mappedBy: 'merchant', cascade: ['persist', 'remove'])]
    private Collection $outletDetails;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 50)]
    private $code;

    #[ORM\Column(type: 'date')]
    private $registrationDate;

    #[ORM\Column(type: 'boolean')]
    private $isActive;

    #[ORM\Column(type: 'string', length: 50)]
    private $clientId;

    // Getters and setters...
    public function __construct()
    {
        $this->transaction = new ArrayCollection();
        $this->details = new MerchantDetails();
        $this->portalUsers = new ArrayCollection();
        $this->outletDetails = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code): void
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * @param mixed $registrationDate
     */
    public function setRegistrationDate($registrationDate): void
    {
        $this->registrationDate = $registrationDate;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * @param mixed $clientId
     */
    public function setClientId($clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return mixed
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * @param mixed $transaction
     */
    public function setTransaction($transaction): void
    {
        $this->transaction = $transaction;
    }

    public function getDetails(): ?MerchantDetails
    {
        return $this->details;
    }

    public function setDetails(?MerchantDetails $details): self
    {
        // unset the owning side of the relation if necessary
        if ($details === null && $this->details !== null) {
            $this->details->setMerchant(null);
        }

        // set the owning side of the relation if necessary
        if ($details !== null && $details->getMerchant() !== $this) {
            $details->setMerchant($this);
        }

        $this->details = $details;
        return $this;
    }

    public function getPortalUsers(): Collection
    {
        return $this->portalUsers;
    }

    public function getOutletDetails(): Collection
    {
        return $this->outletDetails;
    }

    public function addPortalUser(PortalUserDetails $portalUser): self
    {
        if (!$this->portalUsers->contains($portalUser)) {
            $this->portalUsers[] = $portalUser;
            $portalUser->setMerchant($this);
        }
        return $this;
    }

    public function removePortalUser(PortalUserDetails $portalUser): self
    {
        if ($this->portalUsers->removeElement($portalUser)) {
            if ($portalUser->getMerchant() === $this) {
                $portalUser->setMerchant(null);
            }
        }
        return $this;
    }

}
