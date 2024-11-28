<?php

namespace App\Http\Controllers;

use App\Stock;
use App\Product;
use App\Category;
use App\Vendor;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Muestra la página con filtros y datos de stock.
     */
    public function index(Request $request)
{
    // Validar el Request para evitar valores inválidos
    $request->validate([
        'category' => 'nullable|exists:categories,id',
        'product' => 'nullable|exists:products,id',
    ]);

    // Crear consulta base
    $stocksQuery = Stock::with(['product', 'category'])
        ->orderBy('updated_at', 'desc');

    // Aplicar filtros
    if ($request->filled('category')) {
        $stocksQuery->where('category_id', $request->category);
    }

    if ($request->filled('product')) {
        $stocksQuery->where('product_id', $request->product);
    }

    // Obtener los resultados de la consulta
    $stocks = $stocksQuery->get();

    // Calcular la cantidad de desperdicio para cada stock
    $stocks->transform(function ($stock) {
        $stock->waste_quantity = $stock->current_quantity <= 0 ? $stock->stock_quantity : 0;
        return $stock;
    });

     // Obtener categorías, productos y proveedores (vendors) para los filtros
    $categories = Category::all(['id', 'name']);
    $products = Product::all(['id', 'product_name']);
    $vendors = Vendor::all(['id', 'name']);  // Suponiendo que tienes un modelo Vendor

    // Retornar la vista con los datos necesarios
    return view('filter.filter', compact('stocks', 'categories', 'products', 'vendors'));
}

}