<?php

namespace App\Http\Controllers;

use App\Filter; 
use App\Product;
use App\Category;
use App\Vendor;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    /**
     * Muestra la página con filtros y datos de stock.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Validación de los filtros
        $validated = $request->validate([
            'category' => 'nullable|exists:category,id',
            'product' => 'nullable|exists:products,id',
            'vendor' => 'nullable|exists:vendors,id',
        ]);

        // Crear la consulta base para obtener los filtros aplicados
        $filtersQuery = Filter::with(['product', 'category', 'vendor'])
                            ->orderBy('filter_date', 'desc'); // Usamos la fecha de filtrado para ordenar

        // Aplicar los filtros si están presentes en la request
        if ($request->filled('category')) {
            $filtersQuery->where('category_id', $request->category);
        }

        if ($request->filled('product')) {
            $filtersQuery->where('product_id', $request->product);
        }

        if ($request->filled('vendor')) {
            $filtersQuery->where('vendor_id', $request->vendor);
        }

        // Obtener los resultados filtrados con paginación
        $filters = $filtersQuery->paginate(15);

        // Obtener las categorías, productos y proveedores de forma eficiente
        $category = Category::select('id', 'name')->get();
        $products = Product::select('id', 'product_name')->get();
        $vendors = Vendor::select('id', 'name')->get();

        // Pasar los datos a la vista
        return view('filter.filter', compact('filters', 'category', 'products', 'vendors'));
    }

    /**
     * Muestra el formulario para agregar un nuevo filtro.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener categorías, productos y proveedores para el formulario
        $category = Category::all();
        $products = Product::all();
        $vendors = Vendor::all();

        return view('filter.create', compact('category', 'products', 'vendors'));
    }

    /**
     * Almacena un nuevo filtro en la base de datos.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:category,id',
            'vendor_id' => 'required|exists:vendors,id',
            'current_quantity_in_use' => 'required|integer',
            'waste' => 'nullable|numeric',
            'supervisor_filtered' => 'required|string|max:191',
            'filter_date' => 'nullable|date',
        ]);

        // Intentar almacenar el filtro
        try {
            Filter::create($validated);
            return redirect()->route('filter.index')->with('success', 'Filtro creado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '¡Algo salió mal! Por favor, vuelva a intentarlo.']);
        }
    }

    /**
     * Muestra el formulario para editar un filtro existente.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Obtener el filtro a editar
        $filter = Filter::findOrFail($id);

        // Obtener las categorías, productos y proveedores para el formulario
        $category = Category::all();
        $products = Product::all();
        $vendors = Vendor::all();

        return view('filter.edit', compact('filter', 'category', 'products', 'vendors'));
    }

    /**
     * Actualiza un filtro existente en la base de datos.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validar los datos recibidos
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'category_id' => 'required|exists:category,id',
            'vendor_id' => 'required|exists:vendors,id',
            'current_quantity_in_use' => 'required|integer',
            'waste' => 'nullable|numeric',
            'supervisor_filtered' => 'required|string|max:191',
            'filter_date' => 'nullable|date',
        ]);

        // Obtener el filtro a actualizar
        $filter = Filter::findOrFail($id);

        // Intentar actualizar el filtro
        try {
            $filter->update($validated);
            return redirect()->route('filter.index')->with('success', 'Filtro actualizado exitosamente.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => '¡Algo salió mal! Por favor, vuelva a intentarlo.']);
        }
    }

    /**
     * Elimina un filtro de la base de datos.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */

     public function destroy(int $id)
     {
         // Busca y elimina el filtro
         $filter = Filter::findOrFail($id);
         $filter->delete();
     
         return redirect()->route('filter.filter')->with('success', 'Filtro eliminado exitosamente.');
     }



}
