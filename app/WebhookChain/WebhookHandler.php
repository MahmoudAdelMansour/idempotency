<?php

namespace App\WebhookChain;

use App\Models\Webhook;

abstract class WebhookHandler
{
    protected ?WebhookHandler $nextHandler = null;
    public function setNextHandler(WebhookHandler $handler): WebhookHandler
    {
        $this->nextHandler = $handler;
        return $handler;
    }

    abstract public function handle(Webhook $webhook, array $context = []): array;

    protected function handleNext(Webhook $webhook, array $context = []): array
    {
        if ($this->nextHandler) {
            return $this->nextHandler->handle($webhook, $context);
        }

        return $context;
    }
}
