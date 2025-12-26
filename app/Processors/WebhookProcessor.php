<?php

namespace App\Processors;

use App\Factory\WebhookParserFactory;
use App\Models\Webhook;
use App\WebhookChain\ParsePayloadHandler;
use App\WebhookChain\PersistTransactionsHandler;
use App\WebhookChain\UpdateStatusToProcessedHandler;
use App\WebhookChain\UpdateStatusToProcessingHandler;
use App\WebhookChain\WebhookHandler;

class WebhookProcessor
{
    public function __construct(
        private WebhookParserFactory $parserFactory
    ) {}

    public function process(Webhook $webhook): array
    {
        $chain = $this->buildChain();
        try {
            return  $chain->handle($webhook);
        } catch (\Exception $e) {
            $webhook->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function buildChain(): WebhookHandler
    {
     /*
      *Fluent chaining works well for builders, but Chain of Responsibility represents a linked execution flow.
        Returning $this or $handler in a fluent API can unintentionally change the execution entry point.
        To avoid ambiguity and ensure the chain always starts from a single, explicit root handler, I preferred manual wiring.

      * */
        $updateToProcessing = new UpdateStatusToProcessingHandler();
        $parsePayload = new ParsePayloadHandler($this->parserFactory);
        $persistTransactions = new PersistTransactionsHandler();
        $updateToProcessed = new UpdateStatusToProcessedHandler();

        $updateToProcessing->setNextHandler($parsePayload);
        $parsePayload->setNextHandler($persistTransactions);
        $persistTransactions->setNextHandler($updateToProcessed);

        return $updateToProcessing;
    }

}
