<?php

namespace App\Services;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SalesExport;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExcelService
{
    public function downloadSalesReport(Collection $collection, string $fileName): BinaryFileResponse
    {
        return Excel::download(new SalesExport($collection), $fileName);
    }
}
