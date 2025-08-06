<?php

namespace App\Repository\Reports\Group;

use App\Entity\Reports\Group\ReportGroupCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReportGroupCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportGroupCategory::class);
    }

    public function findByFilters($merchantId, $startDate = null, $endDate = null, $detail = null)
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.merchant = :merchantId')
            ->setParameter('merchantId', $merchantId)
            ->orderBy('t.dateTime', 'DESC');

        // Handle date filters (only if detail is empty)
        if (empty($detail)) {
            if ($startDate) {
                try {
                    $startDateObj = new \DateTime($startDate);
                    $startDateObj->setTime(0, 0, 0);
                    $qb->andWhere('t.dateTime >= :startDate')
                        ->setParameter('startDate', $startDateObj);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Invalid start date format');
                }
            }

            if ($endDate) {
                try {
                    $endDateObj = new \DateTime($endDate);
                    $endDateObj->setTime(23, 59, 59);
                    $qb->andWhere('t.dateTime <= :endDate')
                        ->setParameter('endDate', $endDateObj);
                } catch (\Exception $e) {
                    throw new \InvalidArgumentException('Invalid end date format');
                }
            }
        }

        // Handle detail search
        if (!empty($detail)) {
            $qb->andWhere('LOWER(t.detail) LIKE LOWER(:detail)')
                ->setParameter('detail', '%'.addcslashes($detail, '%_').'%');
        }

        return $qb->getQuery()->getResult();
    }
}
