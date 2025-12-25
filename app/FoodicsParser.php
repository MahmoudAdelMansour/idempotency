<?php

namespace App;

use App\Parsing\WebhookParser;
use App\Utility\DateParser;
use Illuminate\Support\Carbon;

class FoodicsParser implements WebhookParser
{

    public function parse(string $payload, string $bankName): array
    {
        $lines = explode("\n", $payload);
        $transactions = [];
        foreach ($lines as $line) {
            [$date,$amount,$reference,$metadata] = $this->parseLine($line);
            $transactions[] = [
                'date' => $date,
                'amount' => $amount,
                'reference' => $reference,
                'metadata' => $metadata
            ];
        }
        return $transactions;
    }



}
