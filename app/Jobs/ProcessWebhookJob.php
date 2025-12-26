<?php

namespace App\Jobs;

use App\Models\Webhook;
use App\Processors\WebhookProcessor;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Webhook $webhook)
    {
    }

    public function handle(WebhookProcessor $processor): void
    {
        if ($this->webhook->status !== 'pending') {
            return;
        }
        $processor->process($this->webhook);
    }
}
