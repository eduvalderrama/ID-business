<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public function getAll()
    {
        return Product::all();
    }

    public function findById($id)
    {
        return Product::find($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update($id, array $data)
    {
        $product = Product::find($id);

        if (!$product) {
            return null;
        }

        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return false;
        }

        $product->delete();
        return true;
    }

    public function updateStock(int $id, int $quantity)
    {
        $product = $this->findById($id);

        if (!$product || $product->stock < $quantity) {
            throw new \Exception("Stock insuficiente para el producto: {$product->nombre}");
        }

        $product->stock -= $quantity;
        $product->save();
    }
}
