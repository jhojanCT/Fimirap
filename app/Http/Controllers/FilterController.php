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
        try {
            // Validar el Request
            $validatedData = $this->validateFilters($request);

            // Obtener los resultados con filtros aplicados
            $stocks = $this->applyFilters($validatedData);

            // Calcular la cantidad de desperdicio para cada stock
            $stocks->transform(fn($stock) => $this->calculateWaste($stock));

            // Cargar datos para los filtros
            $categories = $this->getCategories();
            $products = $this->getProducts();
            $vendors = $this->getVendors();

            // Retornar la vista con datos procesados
            return view('filter.filter', compact('stocks', 'categories', 'products', 'vendors'));
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Error al procesar los datos: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Valida los filtros enviados en el request.
     */
    private function validateFilters(Request $request)
    {
        return $request->validate([
            'category' => 'nullable|exists:categories,id',
            'product' => 'nullable|exists:products,id',
        ]);
    }

    /**
     * Aplica los filtros a la consulta de stocks.
     */
    private function applyFilters(array $filters)
    {
        $query = Stock::with(['product', 'category'])
            ->orderBy('updated_at', 'desc');

        if (!empty($filters['category'])) {
            $query->where('category_id', $filters['category']);
        }

        if (!empty($filters['product'])) {
            $query->where('product_id', $filters['product']);
        }

        return $query->get();
    }

    /**
     * Calcula el desperdicio de un stock.
     */
    private function calculateWaste($stock)
    {
        $stock->waste_quantity = $stock->current_quantity <= 0 ? $stock->stock_quantity : 0;
        return $stock;
    }

    /**
     * Obtiene todas las categorías disponibles.
     */
    private function getCategories()
    {
        return Category::all(['id', 'name']);
    }

    /**
     * Obtiene todos los productos disponibles.
     */
    private function getProducts()
    {
        return Product::all(['id', 'product_name']);
    }

    /**
     * Obtiene todos los proveedores disponibles.
     */
    private function getVendors()
    {
        return Vendor::all(['id', 'name']);
    }
}
