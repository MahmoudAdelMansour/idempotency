<?php

namespace App\Parsing;

interface WebhookParser
{
    /**
     * Parses raw payload and returns an array of transaction arrays
     *
     * @param string $payload
     * @param string $bankName
     * @return array<int, array<string, mixed>>
     */
    public function parse(string $payload, string $bankName): array;
}
