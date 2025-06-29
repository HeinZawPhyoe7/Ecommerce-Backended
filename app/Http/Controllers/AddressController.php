<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function store(Request $request)
    {

        $user = Auth::user();

        $request->validate([
            'address' => 'required|string|max:255',
            'house_address' => 'required|string|max:255',
            'unit_floor' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'type' => 'required|string|max:255',
            'product_id' => 'required|numeric',
        ]);

        $address = new Address();
        $address->address = $request->address;
        $address->house_address = $request->house_address;
        $address->unit_floor = $request->unit_floor;
        $address->recipient_name = $request->recipient_name;
        $address->phone = $request->phone;
        $address->type = $request->type;
        $address->user_id = $user->id;
        $address->product_id = $request->product_id;
        $address->save();

        return response()->json([
            'message' => 'success',
            'address' => $address
        ], 201);
    }
}
