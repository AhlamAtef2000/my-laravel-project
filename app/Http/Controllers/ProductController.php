<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller {
    public function index()
    {
        $products = Product::where('active', true)
        ->latest()
        ->paginate(10);

        return view('products.index', compact('products'));
    }
    public function show(Product $product)
    {
        if(!$product->active) {
            abort(404);
        }
        return view('products.show', compact('product'));
    }
}
