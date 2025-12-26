<?php

namespace Database\Factories;

use App\Models\Webhook;
use Illuminate\Database\Eloquent\Factories\Factory;

class WebhookFactory extends Factory
{
    protected $model = Webhook::class;

    public function definition(): array
    {
        // Default: Foodics
        return $this->foodicsBank();
    }

    // State method for Foodics
    public function foodics(): static
    {
        return $this->state(fn () => $this->foodicsBank());
    }

    // State method for Acme
    public function acme(): static
    {
        return $this->state(fn () => $this->acmeBank());
    }

    private function foodicsBank(): array
    {
        $duplicateReference = $this->generateReference();
        $lines = collect(range(1, 2))
            ->map(fn () => "20250615156,50#{$this->generateReference()}#note/debtmarch/internal_reference/REFINT/A462JE81")
            ->implode("\n");

        return [
            'bank_name' => 'foodics_bank',
            'status' => 'pending',
            'payload' => json_encode(trim("
20250615156,50#{$duplicateReference}#note/debtmarch/internal_reference/REFINT/A462JE81
20250615156,50#{$duplicateReference}#note/debtmarch/internal_reference/REFINT/A462JE81
{$lines}
            ")),
            'received_at' => now(),
        ];
    }

    private function acmeBank(): array
    {
        $duplicateReference = $this->generateReference();
        $lines = collect(range(1, 2))
            ->map(fn () => "156,50//{$this->generateReference()}//20250615")
            ->implode("\n");

        return [
            'bank_name' => 'acme_bank',
            'status' => 'pending',
            'payload' => json_encode(trim("
156,50//{$duplicateReference}//20250615
{$lines}
            ")),
            'received_at' => now(),
        ];
    }

    private function generateReference(): string
    {
        return str_pad(random_int(1, 999999999999999), 20, '0', STR_PAD_LEFT);
    }
}
