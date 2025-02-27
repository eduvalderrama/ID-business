<?php

namespace App\Http\Controllers;

use App\Services\ExcelService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\SaleService;
use Carbon\Carbon;

class SaleController extends Controller
{
    private SaleService $saleService;
    private ExcelService $excelService;

    public function __construct(SaleService $saleService, ExcelService $excelService)
    {
        $this->saleService = $saleService;
        $this->excelService = $excelService;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cliente_nombre' => 'required|string|max:255',
            'cliente_identificacion_tipo' => 'required|in:DNI,RUC',
            'cliente_identificacion' => 'required|string|max:20',
            'cliente_email' => 'nullable|email',
            'productos' => 'required|array',
            'productos.*.id' => 'required|exists:products,id',
            'productos.*.cantidad' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $venta = $this->saleService->createSale($request->all());

            return response()->json([
                'message' => 'Venta registrada con éxito',
                'venta' => $venta
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error registrando la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'format' => 'required|in:json,xlsx',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error en la validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date)->startOfDay();
        $endDate = Carbon::parse($request->end_date)->endOfDay();
        $ventas = $this->saleService->getSalesByDateRange($startDate, $endDate);

        if ($ventas->isEmpty()) {
            return response()->json(['message' => 'No se encontraron ventas en el rango de fechas'], 404);
        }

        return $request->format === 'json'
            ? response()->json($ventas)
            :  $this->excelService->downloadSalesReport($ventas, 'reporte_ventas.xlsx');;
    }
}
