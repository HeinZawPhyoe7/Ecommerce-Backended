<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AddressController extends Controller
{
    public function store(Request $request)
    {

        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'address' => 'required|string|max:255',
            'house_address' => 'required|string|max:255',
            'unit_floor' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'type' => 'required|string|max:255',
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        $address = new Address();
        $address->address = $request->address;
        $address->house_address = $request->house_address;
        $address->unit_floor = $request->unit_floor;
        $address->recipient_name = $request->recipient_name;
        $address->phone = $request->phone;
        $address->type = $request->type;
        $address->user_id = $user->id;
        $address->product_ids = $request->product_ids;
        $address->save();

        return response()->json([
            'message' => 'success',
            'address' => $address
        ], 201);
    }

    public function show()
    {
        $userId = Auth::id();
        $addresses = Address::where('user_id', $userId)->get();

        if ($addresses->isEmpty()) {
            return response()->json(['message' => 'Address not found'], 404);
        }

        $addressesWithProducts = $addresses->map(function ($address) {
            $productIds = $address->product_ids;
            $products = Product::whereIn('id', $productIds)->get();

            $addressData = $address->toArray();
            $addressData['productList'] = $products;

            return $addressData;
        });

        return response()->json([
            'message' => 'success',
            'addresses' => $addressesWithProducts,
        ]);
    }

    public function delete(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $address = Address::find($request->addressId);

        if (!$address || $address->user_id !== $user->id) {
            return response()->json(['message' => 'Address not found or unauthorized'], 404);
        }

        $currentProductIds = $address->product_ids ?? [];
        $productIdsToRemove = $request->product_ids ?? $request->productId ?? [];

        // Check if all current product IDs are being removed
        $remainingProductIds = array_values(array_diff($currentProductIds, $productIdsToRemove));

        if (empty($remainingProductIds)) {
            // No products left, delete the whole address row
            $address->delete();

            return response()->json([
                'message' => 'Address deleted because no products remained',
                'code' => 200
            ]);
        }

        // Otherwise, just update the address with remaining products
        $address->product_ids = $remainingProductIds;
        $address->save();

        return response()->json([
            'message' => 'Product IDs removed successfully',
            'address' => $address,
            'code' => 200,
            'remainingProductIds' => $remainingProductIds
        ]);
    }
}
