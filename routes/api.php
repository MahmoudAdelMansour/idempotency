<?php

use App\Http\Controllers\WebhookController ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
 * Content-Type	application/json
 * Example : ["20250615156,50#202506159000001#note/debtmarch/internal_reference/A462JE81"]
 * */
Route::post('webhooks/{bank_name}',[WebhookController::class,'receiveWebhook'])
    ->name('webhooks.store');
