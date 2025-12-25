<?php

namespace App\Parsing;

use App\Utility\DateParser;

class AcmeParser implements WebhookParser
{
//    In first i decide to use a Factory method with a deliamter ( # or // ) for same approach and migrate it with a Builder Pattern later if needed
// But i decided to keep it simple for now as the requirement is only for two parsers.
    public function parse(string $payload, string $bankName): array
    {
        $lines = explode("\n", $payload);
        $transactions = [];
        foreach ($lines as $line) {
            [$date,$amount,$reference,$metadata] = $this->parseLine($line);
            $transactions[] = [
                'reference' => $reference,
                'date' => $date,
                'amount' => $amount,
                'metadata' => $metadata
            ];
        }
        return $transactions;
    }
    private function parseLine(string $line): array
    {
        $parts = explode('//', stripslashes($line));
        // [156,50//202506159000001//20250615]
        $amount = (float) str_replace(',', '.', $parts[0]);
        $reference = $parts[1];
        $date = DateParser::parse($parts[2]);
        $metadata = []; // Acme has no metadata
        return [$date, $amount, $reference, $metadata];
    }

}
