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
        // Validación de los filtros
        $validated = $request->validate([
            'category' => 'nullable|exists:categories,id',
            'product' => 'nullable|exists:products,id',
            'vendor' => 'nullable|exists:vendors,id',
        ]);

        // Crear la consulta base
        $stocksQuery = Stock::with(['product', 'category', 'vendor'])
                            ->orderBy('updated_at', 'desc');

        // Aplicar los filtros si están presentes en la request
        if ($request->has('category')) {
            $stocksQuery->where('category_id', $request->category);
        }

        if ($request->has('product')) {
            $stocksQuery->where('product_id', $request->product);
        }

        if ($request->has('vendor')) {
            $stocksQuery->where('vendor_id', $request->vendor);
        }

        // Obtener los resultados filtrados con paginación
        $stocks = $stocksQuery->paginate(15);

        // Obtener las categorías, productos y proveedores de forma más eficiente
        $categories = Category::select('id', 'name')->get();
        $products = Product::select('id', 'product_name')->get();
        $vendors = Vendor::select('id', 'name')->get();

        // Pasar los datos a la vista
        return view('filter.filter', compact('stocks', 'categories', 'products', 'vendors'));
    }

    /**
     * Muestra el formulario para agregar un nuevo stock.
     */
    public function create()
    {
        // Obtener categorías, productos y proveedores para el formulario
        $categories = Category::all();
        $products = Product::all();
        $vendors = Vendor::all();

        return view('filter.create', compact('categories', 'products', 'vendors'));
    }

    /**
     * Almacena un nuevo stock en la base de datos.
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'quantity' => 'required|integer',
        ]);

        // Crear un nuevo stock
        Stock::create($validated);

        // Redirigir a la lista de stocks con un mensaje de éxito
        return redirect()->route('filter.index')->with('success', 'Stock creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un stock existente.
     */
    public function edit($id)
    {
        // Obtener el stock a editar
        $stock = Stock::findOrFail($id);

        // Obtener las categorías, productos y proveedores para el formulario
        $categories = Category::all();
        $products = Product::all();
        $vendors = Vendor::all();

        return view('filter.edit', compact('stock', 'categories', 'products', 'vendors'));
    }

    /**
     * Actualiza un stock existente en la base de datos.
     */
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:categories,id',
            'vendor_id' => 'required|exists:vendors,id',
            'quantity' => 'required|integer',
        ]);

        // Obtener el stock a actualizar
        $stock = Stock::findOrFail($id);

        // Actualizar los datos del stock
        $stock->update($validated);

        // Redirigir a la lista de stocks con un mensaje de éxito
        return redirect()->route('filter.index')->with('success', 'Stock actualizado exitosamente.');
    }

    /**
     * Elimina un stock de la base de datos.
     */
    public function destroy($id)
    {
        // Obtener el stock a eliminar
        $stock = Stock::findOrFail($id);

        // Eliminar el stock
        $stock->delete();

        // Redirigir a la lista de stocks con un mensaje de éxito
        return redirect()->route('filter.index')->with('success', 'Stock eliminado exitosamente.');
    }
}
