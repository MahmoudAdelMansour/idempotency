<?php

namespace Integration;

use App\Factory\WebhookParserFactory;
use App\Models\Transaction;
use App\Models\Webhook;
use App\Processors\WebhookProcessor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebhookProcessorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_processes_foodics_webhook_successfully()
    {
        $webhook = Webhook::factory()->foodics()->create();
        $processor = new WebhookProcessor(new WebhookParserFactory());

        $result = $processor->process($webhook);

        $this->assertEquals('processed', $webhook->fresh()->status);
        $this->assertNotEmpty($result['transactions']);
        $this->assertDatabaseHas('transactions', [
            'bank_name' => 'foodics_bank',
        ]);
    }

    /** @test */
    public function it_processes_acme_webhook_successfully()
    {
        $webhook = Webhook::factory()->acme()->create();
        $processor = new WebhookProcessor(new WebhookParserFactory());

        $result = $processor->process($webhook);

        $this->assertEquals('processed', $webhook->fresh()->status);
        $this->assertDatabaseHas('transactions', [
            'bank_name' => 'acme_bank',
        ]);
    }

    /** @test */
    public function it_handles_duplicate_transactions_idempotently()
    {
        $webhook = Webhook::factory()->foodics()->create();
        $processor = new WebhookProcessor(new WebhookParserFactory());

        // Process first time
        $processor->process($webhook);
        $firstCount = Transaction::count();

        // Process again (simulating duplicate webhook)
        $webhook->update(['status' => 'pending']); // Reset
        $processor->process($webhook);
        $secondCount = Transaction::count();

        // Should not create duplicates
        $this->assertEquals($firstCount, $secondCount);
    }

    /** @test */
    public function it_marks_webhook_as_failed_on_exception()
    {
        $webhook = Webhook::factory()->create([
            'payload' => json_encode('invalid format'),
        ]);
        $processor = new WebhookProcessor(new WebhookParserFactory());

        try {
            $processor->process($webhook);
        } catch (\Exception $e) {
            // Expected
        }

        $this->assertEquals('failed', $webhook->fresh()->status);
    }
}
