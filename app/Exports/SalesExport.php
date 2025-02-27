<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SalesExport implements FromCollection, WithHeadings
{
    protected $ventas;

    public function __construct($ventas)
    {
        $this->ventas = $ventas;
    }

    public function collection()
    {
        return $this->ventas->map(function ($venta) {
            return [
                'Código' => $venta->codigo,
                'Nombre Cliente' => $venta->cliente_nombre,
                'Identificación Cliente' => $venta->cliente_identificacion_tipo . ' ' . $venta->cliente_identificacion,
                'Correo Cliente' => $venta->cliente_email ?? 'N/A',
                'Cantidad Productos' => $venta->cantidad,
                'Monto Total' => $venta->monto_total,
                'Fecha y Hora' => $venta->created_at->format('Y-m-d H:i A'),
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Código', 'Nombre Cliente', 'Identificación Cliente', 'Correo Cliente',
            'Cantidad Productos', 'Monto Total', 'Fecha y Hora'
        ];
    }
}