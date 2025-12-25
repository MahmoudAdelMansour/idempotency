<?php

namespace App\WebhookChain;

use App\Models\Webhook;
use App\WebhookChain\WebhookHandler;

class UpdateStatusToProcessingHandler extends WebhookHandler
{

    public function handle(Webhook $webhook, array $context = []): array
    {
        $webhook->update(['status' => 'processed']);
        return $this->handleNext($webhook, $context);

    }
}
