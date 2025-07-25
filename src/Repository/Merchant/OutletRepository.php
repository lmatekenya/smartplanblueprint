<?php

namespace App\Repository\Merchant;

use App\Entity\Merchant;
use App\Entity\Merchant\OutletDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OutletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OutletDetails::class);
    }
//    public function findByMerchant(int $id): ?Merchant
//    {
//        return $this->createQueryBuilder('m')
//            ->leftJoin('m.merchant', 'd')
//            ->addSelect('d') // Important for performance
//            ->where('m.id = :id')
//            ->setParameter('id', $id)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }

// In repository
    public function findByMerchant(int $merchantId): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->getQuery()
            ->getResult();
    }

}
