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
    private function parseLine(string $line): array
    {
        $parts = explode('#', $line);
        // Date,Amount Parsing
        [$dateString, $amountString] = explode(',', $parts[0], 2);
        $date = DateParser::parse($dateString);
        $amount = (float) str_replace(',', '.', $amountString);
        $reference = $parts[1];
        $metadata = $this->parseMetadata($parts[2]);

        return [$date, $amount, $reference, $metadata];
    }
    private function parseMetadata(string $metadataString): array
    {

        $pairs = explode('/', $metadataString);
        $metadata = [];

        // Loop through pairs: key, value, key, value...
        for ($i = 0; $i < count($pairs); $i += 2) {
            if (isset($pairs[$i + 1])) {
                $metadata[$pairs[$i]] = $pairs[$i + 1];
            }
        }
        return $metadata;
    }


}
