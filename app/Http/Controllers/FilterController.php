<?php
namespace App\Http\Controllers;

use App\Stock;
use App\Product;
use App\Category;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index(Request $request)
    {
        // Definimos las variables para las consultas, utilizando el Request para obtener filtros
        $stock = Stock::with('product', 'category')
            ->orderBy('updated_at', 'desc');

        // Filtrar por categoría (opcional)
        if ($request->has('category') && $request->category != '') {
            $stock->where('category_id', $request->category);
        }

        // Filtrar por producto (opcional)
        if ($request->has('product') && $request->product != '') {
            $stock->where('product_id', $request->product);
        }

        // Obtener los resultados filtrados
        $stocks = $stock->get();

        // Calcular la cantidad de desperdicio
        $stocks = $stocks->map(function ($stock) {
            // Calcular el desperdicio (por ejemplo, si la cantidad actual es 0)
            $stock->waste_quantity = $stock->current_quantity <= 0 ? $stock->stock_quantity : 0;
            return $stock;
        });

        // Obtener todas las categorías y productos para los filtros
        $categories = Category::all();
        $products = Product::all();

        // Devolver la vista con los datos
        return view('filter.filter', compact('stocks', 'categories', 'products'));
    }
}
