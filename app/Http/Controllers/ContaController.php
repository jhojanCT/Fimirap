<?php

namespace App\Http\Controllers;

use App\Product;
use App\Stock;
use App\Category;
use Session;
use Illuminate\Http\Request; 

class ContaController extends Controller
{
    public function index()
    {

        // Pasar los datos a la vista
        return view('conta.index', compact('menus'));
    }
}
