<?php

namespace App\Repository\LimitsAndDocs;

use App\Entity\LimitsAndDocs\Provider;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Provider>
 */
class ProviderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Provider::class);
    }

//    public function findByMerchant(int $merchantId): array
//    {
//        return $this->createQueryBuilder('p')
//            ->where('p.merchant = :merchantId')
//            ->setParameter('merchantId', $merchantId)
//            ->orderBy('p.dateCreated', 'DESC')
//            ->getQuery()
//            ->getResult();
//    }

    // src/Repository/ProviderRepository.php
    public function findByMerchant(int $merchantId, array $filters = [], string $sort = 'dateCreated', string $direction = 'DESC'): array
    {
        $qb = $this->createQueryBuilder('p')
            ->where('p.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId);

        // Add filters
        if (!empty($filters['type'])) {
            $qb->andWhere('p.type = :type')
                ->setParameter('type', $filters['type']);
        }

        if (isset($filters['isEnabled'])) {
            $qb->andWhere('p.isEnabled = :isEnabled')
                ->setParameter('isEnabled', $filters['isEnabled']);
        }

        // Add sorting
        $qb->orderBy('p.' . $sort, $direction);

        return $qb->getQuery()->getResult();
    }
}
