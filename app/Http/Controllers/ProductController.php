<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();

        return Inertia::render('Product', [
            'products' => $products,
        ]);
    }

    public function store()
    {
        
    }

    public function update()
    {

    }

    public function destroy()
    {

    }
}
