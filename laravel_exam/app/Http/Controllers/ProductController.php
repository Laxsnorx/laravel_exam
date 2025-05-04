<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; 

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        return response()->json($query->get(), 200); // Return the list of products
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Method for showing the create form (Not used in API typically)
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $validate = $request->validate([
        'name' => 'required|string',
        'description' => 'required|string',
        'model' => 'required|numeric',
        'image' => 'nullable|mimes:jpeg,png,jpg|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $validate['image'] = $path;
    }

    $product = Product::create($validate);

    return response()->json([
        'message' => 'Product created successfully',
        'product' => $product,
        'image_url' => $product->image ? asset('storage/' . $product->image) : null
    ], 201); // Return 201 for resource created
}


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $validate = $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
            'model' => 'required|numeric',
            'image' => 'nullable|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // If there's an existing image, delete it
            if ($product->image) {
                Storage::delete('public/' . $product->image);
            }
            $path = $request->file('image')->store('images', 'public');
            $validate['image'] = $path;
        }

        $product->update($validate);

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $product = Product::find($id);
        if (empty($product)) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Delete the image if it exists
        if ($product->image) {
            Storage::delete('public/' . $product->image);
        }

        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ], 200);
    }
}
