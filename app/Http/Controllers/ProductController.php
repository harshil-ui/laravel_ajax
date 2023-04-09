<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();

        return view('products.index', compact('products'));
    }


    public function create()
    {
        return view('products.insert');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'upc' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $imageName = time() . '.' . $request->image->extension();

        $request->image->storeAs('public/images', $imageName);

        $record = new Product([
            'name' => $request->get('name'),
            'price' => $request->get('price'),
            'upc' => $request->get('upc'),
            'status' => $request->get('status'),
            'image' => $imageName,
        ]);

        $record->save();

        return redirect('products');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        return view('products.edit', ['product' => $product]);
    }

    public function update($id, Request $request)
    {

        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'upc' => 'required',
            'status' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::find($id);

        if ($request->hasFile('image')) {
            Storage::delete('public/images/' . $product->image);

            $imageName = time() . '.' . $request->image->extension();

            $request->image->storeAs('public/images', $imageName);

            $product->image = $imageName;
        }

        $product->name = $request->get('name');
        $product->price = $request->get('price');
        $product->upc = $request->get('upc');
        $product->status = $request->get('status');
        $product->save();

        return redirect('products');
    }


    public function deleteSingleProduct($id)
    {
        // $id = $request->get('id');

        $product = Product::find($id);
        if ($product->image) {
            Storage::delete('public/images/' . $product->image);
        }

        $product->delete();
    }

    public function deleteMultipleProducts(Request $request)
    {
        // return $request->data;
        $ids = $request->get('ids');

        // $records = Product::whereIn('id', $ids)->get();

        Product::whereIn('id', $ids)->delete();
    }
}
