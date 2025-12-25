<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'client_id', 'bank_name', 'bank_reference', 'amount', 'currency', 'date', 'raw_payload',
    ];
}
