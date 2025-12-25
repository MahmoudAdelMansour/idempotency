<?php

namespace App\Factory;

use App\Parsing\AcmeParser;
use App\Parsing\FoodicsParser;
use App\Parsing\WebhookParser;
use InvalidArgumentException;

class WebhookParserFactory
{
    private array $parsers = [];

    public function __construct()
    {
        $this->parsers = [
            'foodics_bank' => new FoodicsParser(),
            'acme_bank' => new AcmeParser(),
        ];
    }

    public function getParser(string $bankName): WebhookParser
    {
        if (!isset($this->parsers[$bankName])) {
            throw new InvalidArgumentException("No parser found for bank: {$bankName}");
        }

        return $this->parsers[$bankName];
    }
}
