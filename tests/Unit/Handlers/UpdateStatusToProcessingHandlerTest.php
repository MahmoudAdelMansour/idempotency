<?php

namespace Tests\Unit\Handlers;

use App\Models\Webhook;
use App\WebhookChain\UpdateStatusToProcessingHandler;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateStatusToProcessingHandlerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_updates_webhook_status_to_processing()
    {
        $webhook = Webhook::factory()->create(['status' => 'pending']);
        $handler = new UpdateStatusToProcessingHandler();

        $handler->handle($webhook, []);

        $this->assertEquals('processing', $webhook->fresh()->status);
    }

    /** @test */
    public function it_passes_context_to_next_handler()
    {
        $webhook = Webhook::factory()->create();
        $handler = new UpdateStatusToProcessingHandler();

        $context = ['test' => 'data'];
        $result = $handler->handle($webhook, $context);

        $this->assertEquals($context, $result);
    }
}
