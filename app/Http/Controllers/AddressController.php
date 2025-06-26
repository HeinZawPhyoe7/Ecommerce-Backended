<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'address' => 'required|string|max:255',
            'house_address' => 'required|string|max:255',
            'unit_floor' => 'required|string|max:255',
            'recipient_name' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'type' => 'required|string|max:255',
        ]);

        $address = new Address();
        $address->address = $request->address;
        $address->house_address = $request->house_address;
        $address->unit_floor = $request->unit_floor;
        $address->recipient_name = $request->recipient_name;
        $address->phone = $request->phone;
        $address->type = $request->type;
        $address->save();

        return response()->json([
            'message' => 'success',
            'address' => $address
        ], 201);
    }
}
