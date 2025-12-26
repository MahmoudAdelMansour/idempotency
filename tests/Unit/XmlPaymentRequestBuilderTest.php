<?php

namespace Tests\Unit;

use App\Builder\XmlPaymentRequestBuilder;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class XmlPaymentRequestBuilderTest extends TestCase
{
    public function test_it_generates_required_xml_structure()
    {
        $tx = $this->transaction();

        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo(
                $tx['reference'],
                $tx['date'],
                $tx['amount'],
                $tx['currency']
            )
            ->setSender('SA6980000204608016212908')
            ->setReceiver(
                'FDCSSARI',
                'SA6980000204608016211111',
                'Jane Doe'
            )
            ->build();

        $this->assertStringContainsString('<PaymentRequestMessage>', $xml);
        $this->assertStringContainsString('<TransferInfo>', $xml);
        $this->assertStringContainsString('<SenderInfo>', $xml);
        $this->assertStringContainsString('<ReceiverInfo>', $xml);
    }

    public function test_transfer_info_is_correct()
    {
        $tx = $this->transaction();

        $xml = (new XmlPaymentRequestBuilder())
            ->setTransferInfo(
                $tx['reference'],
                $tx['date'],
                $tx['amount'],
                $tx['currency']
            )
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->build();

        $this->assertStringContainsString(
            '<Reference>00000604690223790483</Reference>',
            $xml
        );

        $this->assertStringContainsString(
            '<Amount>50.00</Amount>',
            $xml
        );

        $this->assertStringContainsString(
            '<Currency>SAR</Currency>',
            $xml
        );

        $this->assertStringContainsString(
            '<Date>2025-06-15',
            $xml
        );
    }
    public function test_notes_are_rendered_when_present()
    {
        $tx = $this->transaction();

        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo(
                $tx['reference'],
                $tx['date'],
                $tx['amount'],
                $tx['currency']
            )
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->setNotes(array_values($tx['metadata']))
            ->build();

        $this->assertStringContainsString('<Notes>', $xml);
        $this->assertStringContainsString('<Note>debtmarch</Note>', $xml);
        $this->assertStringContainsString('<Note>REFINT</Note>', $xml);
    }
    public function test_payment_type_is_rendered_when_not_99()
    {
        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo('ref', now(), 10, 'SAR')
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->setPaymentType(421)
            ->build();

        $this->assertStringContainsString('<PaymentType>421</PaymentType>', $xml);
    }

    public function test_charge_details_is_skipped_when_sha()
    {
        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo('ref', now(), 10, 'SAR')
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->setChargeDetails('SHA')
            ->build();

        $this->assertStringNotContainsString('<ChargeDetails>', $xml);
    }
    public function test_generated_xml_is_valid()
    {
        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo('ref', now(), 10, 'SAR')
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->build();

        $this->assertTrue(simplexml_load_string($xml) !== false);
    }


    public function test_notes_are_not_rendered_when_empty()
    {
        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo('ref', now(), 10, 'SAR')
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->setNotes([])
            ->build();

        $this->assertStringNotContainsString('<Notes>', $xml);
    }
    public function test_payment_type_is_skipped_when_99()
    {
        $xml = new XmlPaymentRequestBuilder()
            ->setTransferInfo('ref', now(), 10, 'SAR')
            ->setSender('SA1')
            ->setReceiver('FDCSSARI', 'SA2', 'Jane Doe')
            ->setPaymentType(99)
            ->build();

        $this->assertStringNotContainsString('<PaymentType>', $xml);
    }

    private function transaction(): array
    {
        return [
            'reference' => '00000604690223790483',
            'date' => Carbon::parse('2025-06-15 00:00:00'),
            'amount' => 50.00,
            'currency' => 'SAR',
            'metadata' => [
                'note' => 'debtmarch',
                'internal_reference' => 'REFINT',
            ],
        ];
    }
}
