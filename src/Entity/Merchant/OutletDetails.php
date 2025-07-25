<?php

namespace App\Entity\Merchant;
use AllowDynamicProperties;
use App\Entity\Merchant;
use App\Repository\Merchant\OutletRepository;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: OutletRepository::class)]
class OutletDetails
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Merchant::class, inversedBy: 'outlets')]
    #[ORM\JoinColumn(name: "merchant_id", referencedColumnName: "id")]
    private Merchant $merchant;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $account;

    #[ORM\Column(type: 'string')]
    private string $type;

    #[ORM\Column(type: 'integer')]
    private int $telephone;

    #[ORM\Column(type: 'string')]
    private string $city;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $dateCreated;

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

    public function getMerchant(): Merchant
    {
        return $this->merchant;
    }

    public function setMerchant(Merchant $merchant): void
    {
        $this->merchant = $merchant;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function setAccount(string $account): void
    {
        $this->account = $account;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function isTelephone(): int
    {
        return $this->telephone;
    }

    public function getTelephone(): int
    {
        return $this->telephone;
    }


    public function setTelephone(int $telephone): void
    {
        $this->telephone = $telephone;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function getDateCreated(): \DateTimeInterface
    {
        return $this->dateCreated;
    }

    public function setDateCreated(\DateTimeInterface $dateCreated): void
    {
        $this->dateCreated = $dateCreated;
    }


}
