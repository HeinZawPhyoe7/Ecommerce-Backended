<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['address', 'house_address', 'unit_floor', 'recipient_name', 'phone', 'type'];
}
