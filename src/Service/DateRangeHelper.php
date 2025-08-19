<?php

namespace App\Service;

use App\ValueObject\DateRange;

class DateRangeHelper
{
//    public function getDateRange(string $range): DateRange
//    {
//        $today = new \DateTime();
//        $dateRange = new DateRange();
//        $dateRange->setCurrentRange($range);
//
//        switch ($range) {
//            case 'today':
//                $dateRange->setStartDate(clone $today);
//                $dateRange->setEndDate(clone $today);
//                $dateRange->setDisplay($today->format('F j, Y'));
//                break;
//            case 'week':
//                $startDate = clone $today;
//                $startDate->modify('-6 days');
//                $dateRange->setStartDate($startDate);
//                $dateRange->setEndDate(clone $today);
//                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
//                break;
//            case 'month':
//                $startDate = new \DateTime('first day of this month');
//                $dateRange->setStartDate($startDate);
//                $dateRange->setEndDate(clone $today);
//                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
//                break;
//            default:
//                throw new \InvalidArgumentException("Invalid date range specified: $range");
//        }
//
//        return $dateRange;
//    }

    public function getDateRange(string $range): DateRange
    {
        $today = new \DateTime();
        $dateRange = new DateRange();
        $dateRange->setCurrentRange($range);

        switch ($range) {
            case 'today':
                $startDate = (clone $today)->setTime(0, 0, 0);
                $endDate = (clone $today)->setTime(23, 59, 59);
                $dateRange->setStartDate($startDate);
                $dateRange->setEndDate($endDate);
                $dateRange->setDisplay($today->format('F j, Y'));
                break;
            case 'week':
                $startDate = (clone $today)
                    ->modify('-6 days')
                    ->setTime(0, 0, 0);
                $endDate = (clone $today)->setTime(23, 59, 59);
                $dateRange->setStartDate($startDate);
                $dateRange->setEndDate($endDate);
                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
                break;
            case 'month':
                $startDate = (new \DateTime('first day of this month'))
                    ->setTime(0, 0, 0);
                $endDate = (clone $today)->setTime(23, 59, 59);
                $dateRange->setStartDate($startDate);
                $dateRange->setEndDate($endDate);
                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
                break;
            default:
                throw new \InvalidArgumentException("Invalid date range specified: $range");
        }

        return $dateRange;
    }
}
