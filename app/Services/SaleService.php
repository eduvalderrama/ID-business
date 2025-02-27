<?php

namespace App\Services;

use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SaleService
{
    private SaleRepository $saleRepository;
    private ProductRepository $productRepository;

    public function __construct(SaleRepository $saleRepository, ProductRepository $productRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
    }

    public function createSale(array $data)
    {
        DB::beginTransaction();

        try {
            $montoTotal = 0;
            $cantidadTotal = 0;

            foreach ($data['productos'] as $producto) {
                $this->productRepository->updateStock($producto['id'], $producto['cantidad']);

                $product = $this->productRepository->findById($producto['id']);
                $montoTotal += $product->precio_unitario * $producto['cantidad'];
                $cantidadTotal += $producto['cantidad'];
            }

            $venta = $this->saleRepository->create([
                'codigo' => 'VENTA-' . strtoupper(Str::uuid()),
                'cliente_nombre' => $data['cliente_nombre'],
                'cliente_identificacion_tipo' => $data['cliente_identificacion_tipo'],
                'cliente_identificacion' => $data['cliente_identificacion'],
                'cliente_email' => $data['cliente_email'] ?? null,
                'cantidad' => $cantidadTotal,
                'vendedor_id' => auth()->id(),
                'monto_total' => $montoTotal,
            ]);

            DB::commit();
            return $venta;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getSalesByDateRange(string $startDate, string $endDate)
    {
        return $this->saleRepository->findByDateRange($startDate, $endDate);
    }
}
