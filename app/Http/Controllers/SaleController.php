<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SaleController extends Controller
{
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

        DB::beginTransaction();

        try {
            $montoTotal = 0;
            foreach ($request->productos as $producto) {
                $product = Product::findOrFail($producto['id']);

                if ($product->stock < $producto['cantidad']) {
                    throw new \Exception("Stock insuficiente para el producto: {$product->nombre}");
                }

                $product->stock -= $producto['cantidad'];
                $product->save();

                $montoTotal += $product->precio_unitario * $producto['cantidad'];
            }

            $venta = Sale::create([
                'codigo' => 'VENTA-' . strtoupper(Str::uuid()),
                'cliente_nombre' => $request->cliente_nombre,
                'cliente_identificacion_tipo' => $request->cliente_identificacion_tipo,
                'cliente_identificacion' => $request->cliente_identificacion,
                'cliente_email' => $request->cliente_email,
                'vendedor_id' => auth()->id(),
                'monto_total' => $montoTotal,
            ]);

            DB::commit();

            return response()->json([
                'message' => 'Venta registrada con éxito',
                'venta' => $venta
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Error registrando la venta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
