<?php

namespace App\WebhookChain;

use App\Models\Webhook;
use App\WebhookChain\WebhookHandler;

class UpdateStatusToProcessingHandler extends WebhookHandler
{

    public function handle(Webhook $webhook, array $context = []): array
    {
        // TODO: Implement handle() method.
    }
}
