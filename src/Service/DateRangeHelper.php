<?php

namespace App\Service;

use App\ValueObject\DateRange;

class DateRangeHelper
{
    public function getDateRange(string $range): DateRange
    {
        $today = new \DateTime();
        $dateRange = new DateRange();
        $dateRange->setCurrentRange($range);

        switch ($range) {
            case 'today':
                $dateRange->setStartDate(clone $today);
                $dateRange->setEndDate(clone $today);
                $dateRange->setDisplay($today->format('F j, Y'));
                break;
            case 'week':
                $startDate = clone $today;
                $startDate->modify('-7 days');
                $dateRange->setStartDate($startDate);
                $dateRange->setEndDate(clone $today);
                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
                break;
            case 'month':
                $startDate = new \DateTime('first day of this month');
                $dateRange->setStartDate($startDate);
                $dateRange->setEndDate(clone $today);
                $dateRange->setDisplay($startDate->format('F j') . ' - ' . $today->format('F j, Y'));
                break;
            default:
                throw new \InvalidArgumentException("Invalid date range specified: $range");
        }

        return $dateRange;
    }
}
