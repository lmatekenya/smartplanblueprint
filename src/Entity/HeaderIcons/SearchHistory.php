<?php
//
//namespace App\Entity\HeaderIcons;
//
//use App\Entity\User;
//use Doctrine\ORM\Mapping as ORM;
//
///**
// * @ORM\Entity(repositoryClass=SearchHistoryRepository::class)
// * @ORM\Table(name="search_history")
// */
//class SearchHistory
//{
//    /**
//     * @ORM\Id
//     * @ORM\GeneratedValue
//     * @ORM\Column(type="integer")
//     */
//    private $id;
//
//    /**
//     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="searchHistories")
//     * @ORM\JoinColumn(nullable=false)
//     */
//    private $user;
//
//    /**
//     * @ORM\Column(type="string", length=255)
//     */
//    private $query;
//
//    /**
//     * @ORM\Column(type="datetime")
//     */
//    private $createdAt;
//
//    public function getId(): ?int { return $this->id; }
//
//    public function getUser(): ?User { return $this->user; }
//    public function setUser(?User $user): self { $this->user = $user; return $this; }
//
//    public function getQuery(): ?string { return $this->query; }
//    public function setQuery(string $query): self { $this->query = $query; return $this; }
//
//    public function getCreatedAt(): ?\DateTimeInterface { return $this->createdAt; }
//    public function setCreatedAt(\DateTimeInterface $createdAt): self { $this->createdAt = $createdAt; return $this; }
//}


namespace App\Entity\HeaderIcons;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SearchHistoryRepository::class)
 * @ORM\Table(name="search_history")
 */
class SearchHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="searchHistories")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $query;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getQuery(): ?string
    {
        return $this->query;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
