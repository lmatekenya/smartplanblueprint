<?php

namespace App\Entity\LimitsAndDocs;

use App\Entity\Merchant;
use App\Repository\LimitsAndDocs\AgreementRepository;
use Doctrine\ORM\Mapping as ORM;


#[ORM\Entity(repositoryClass: AgreementRepository::class)]
#[ORM\Table(name: 'agreements')]
class Agreement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Merchant::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Merchant $merchant;

    #[ORM\Column(length: 50)]
    private string $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $documentPath;

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
    public function getMerchant()
    {
        return $this->merchant;
    }

    /**
     * @param mixed $merchant
     */
    public function setMerchant($merchant): void
    {
        $this->merchant = $merchant;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type): void
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getDocumentPath()
    {
        return $this->documentPath;
    }

    /**
     * @param mixed $documentPath
     */
    public function setDocumentPath($documentPath): void
    {
        $this->documentPath = $documentPath;
    }

    // Getters and setters...

}
