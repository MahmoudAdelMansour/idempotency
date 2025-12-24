<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{

    protected $fillable = ['bank_name', 'status', 'payload', 'received_at',];
}
