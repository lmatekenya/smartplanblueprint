<?php

namespace App\Repository\LimitsAndDocs;

use App\Entity\LimitsAndDocs\AgreementCommission;
use App\Entity\Merchant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class AgreementCommissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgreementCommission::class);
    }
    public function findByMerchantAndType(Merchant $merchant, string $type): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.merchant = :merchant')
            ->andWhere('c.type = :type')
            ->andWhere('c.isActive = true')
            ->setParameter('merchant', $merchant)
            ->setParameter('type', $type)
            ->orderBy('c.serviceName', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
