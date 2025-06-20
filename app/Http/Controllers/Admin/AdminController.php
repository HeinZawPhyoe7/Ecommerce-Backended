<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'images' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|in:USD,MMK,THB,SGD',
            'exportFrom' => 'required|string|max:255',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->category = $request->category;
        $product->brand = $request->brand;
        $base64String = $request->images;

        if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $type)) {
            $cleanedBase64 = substr($base64String, strpos($base64String, ',') + 1);
            $imageType = strtolower($type[1]);

            $decodedImage = base64_decode($cleanedBase64);

            if ($decodedImage === false) {
                return response()->json(['message' => 'Base64 decode failed'], 400);
            }

            $fileName = uniqid() . '.' . $imageType;
            Storage::disk('public')->put("images/{$fileName}", $decodedImage);

            $product->images = $cleanedBase64;
        }
        $product->description = $request->description;
        $product->price = $request->price;
        $product->currency = $request->currency;
        $product->exportFrom = $request->exportFrom;
        $product->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function getall()
    {
        $products = Product::all();

        return response()->json(
            [
                'message' => 'success',
                'products' => $products
            ]
        );
    }
}
