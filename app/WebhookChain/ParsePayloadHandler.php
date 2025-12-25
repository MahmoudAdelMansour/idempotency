<?php

namespace App\WebhookChain;

use App\Factory\WebhookParserFactory;
use App\Models\Webhook;
use App\WebhookChain\WebhookHandler;

class ParsePayloadHandler extends WebhookHandler
{
    public function __construct(
        private WebhookParserFactory $parserFactory
    ) {}

    public function handle(Webhook $webhook, array $context = []): array
    {
        $parser = $this->parserFactory->getParser($webhook->bank_name);
        $transactions = $parser->parse($webhook->payload, $webhook->bank_name);
        $context['transactions'] = $transactions;

        return $this->handleNext($webhook, $context);

    }
}
