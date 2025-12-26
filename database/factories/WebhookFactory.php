<?php

namespace Database\Factories;

use App\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookDublicateFactory extends Factory
{
    protected $model = Webhook::class;

    public function definition(): array
    {
        // generate referance number random with length of 202506159000001 twice in same record
        $reference = str_pad(random_int(1, 999999999999999), 20, '0', STR_PAD_LEFT);
        return [
            // Random between Foodics Bank and Acme Bank
            'bank_name' => "foodics_bank",
            'status' => 'pending',
            'payload' =>  json_encode(trim("
            20250615156,50#{$reference}#note/debtmarch/internal_reference/REFINT/A462JE81
            20250615156,50#{$reference}#note/debtmarch/internal_reference/REFINT/A462JE81
            ")),

            'received_at' => now(),
        ];
    }
}
