<?php

namespace App\Repository\HeaderIcons;


use App\Entity\HeaderIcons\SearchHistory;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class SearchHistoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SearchHistory::class);
    }

    // MerchantRepository.php
    public function searchMerchant(string $query): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.name LIKE :query')
            ->orWhere('m.id LIKE :query')
            ->orWhere('m.category LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

// TransactionRepository.php
    public function searchTransaction(string $query): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.id LIKE :query')
            ->orWhere('t.merchant LIKE :query')
            ->orWhere('t.status LIKE :query')
            ->orWhere('t.amount LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }

// UserRepository.php
    public function searchUser(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.name LIKE :query')
            ->orWhere('u.email LIKE :query')
            ->orWhere('u.role LIKE :query')
            ->orWhere('u.id LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();
    }
}
