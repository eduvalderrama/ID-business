<?php

namespace App\Repositories;

use App\Models\Sale;

class SaleRepository
{
    public function create(array $data)
    {
        return Sale::create($data);
    }

    public function findByDateRange(string $startDate, string $endDate)
    {
        return Sale::whereBetween('created_at', [$startDate, $endDate])->get();
    }
}
