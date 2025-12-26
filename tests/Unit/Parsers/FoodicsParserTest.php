<?php

namespace Tests\Unit\Parsers;


use App\Parsing\FoodicsParser;
use Tests\TestCase;

class FoodicsParserTest extends TestCase
{
    private FoodicsParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new FoodicsParser();
    }

    /** @test */
    public function it_parses_single_transaction()
    {
            $payload = json_encode("20250615156,50#202506159000001#note/debt payment/internal_reference/BlackMAgic/A462JE81");

            $result = $this->parser->parse($payload, 'foodics_bank');

            $this->assertCount(1, $result);
            $this->assertEquals('202506159000001', $result[0]['reference']);
            $this->assertEquals(50, $result[0]['amount']);
            $this->assertEquals('2025-06-15', $result[0]['date']->format('Y-m-d'));
            $this->assertArrayHasKey('note', $result[0]['metadata']);
    }

    /** @test */
    public function it_parses_multiple_transactions()
    {
        $payload = json_encode("20250615156,50#REF001#note/payment1\n20250616200,00#REF002#note/payment2");

        $result = $this->parser->parse($payload, 'foodics_bank');
        $this->assertCount(2, $result);
        $this->assertEquals('REF001', $result[0]['reference']);
        $this->assertEquals('REF002', $result[1]['reference']);
    }

    /** @test */
    public function it_handles_empty_payload()
    {
        $payload = "";

        $result = $this->parser->parse($payload, 'foodics_bank');

        $this->assertEmpty($result);
    }
}
