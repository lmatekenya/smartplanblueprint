<?php

namespace App\ValueObject;

class DateRange
{
    private \DateTimeInterface $startDate;
    private \DateTimeInterface $endDate;
    private string $display;
    private string $currentRange;

    public function getStartDate(): \DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getDisplay(): string
    {
        return $this->display;
    }

    public function setDisplay(string $display): void
    {
        $this->display = $display;
    }

    public function getCurrentRange(): string
    {
        return $this->currentRange;
    }

    public function setCurrentRange(string $currentRange): void
    {
        $this->currentRange = $currentRange;
    }
}
