<?php

namespace App\Controller\Dto;

class Report
{
    public string $name;
    public string $route;
    public array $params;

    public function __construct(string $name, string $route, array $params = [])
    {
        $this->name = $name;
        $this->route = $route;
        $this->params = $params;
    }
}
