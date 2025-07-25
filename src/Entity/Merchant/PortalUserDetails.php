<?php

namespace App\Entity\Merchant;
use App\Entity\Merchant;
use App\Repository\Merchant\PortalUserRepository;
use App\Repository\MerchantRepository;
use Doctrine\ORM\Mapping as ORM;
use AllowDynamicProperties;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: PortalUserRepository::class)]
class PortalUserDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'portalUsers')]
    #[ORM\JoinColumn(name: "merchant_id", referencedColumnName: "id")]
    private Merchant $merchant;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $email;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column(type: 'boolean')]
    private bool $enabled = true;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreated;

    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }

    /**
     * @return mixed
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param mixed $dateCreated
     */
    public function setDateCreated($dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }

}
