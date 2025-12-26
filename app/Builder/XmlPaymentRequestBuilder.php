<?php
namespace App\Builder;

use App\Builder\PaymentRequestBuilder;

class XmlPaymentRequestBuilder implements PaymentRequestBuilder
{
    private \SimpleXMLElement $xml;

    public function __construct()
    {
        $this->xml = new \SimpleXMLElement(
            '<?xml version="1.0" encoding="utf-8"?><PaymentRequestMessage/>'
        );
    }

    public function setTransferInfo(
        string $reference,
        \DateTimeInterface $date,
        float $amount,
        string $currency
    ): self {
        $transfer = $this->xml->addChild('TransferInfo');
        $transfer->addChild('Reference', $reference);
        $transfer->addChild('Date', $date->format('Y-m-d H:i:sP'));
        $transfer->addChild('Amount', number_format($amount, 2, '.', ''));
        $transfer->addChild('Currency', $currency);

        return $this;
    }

    public function setSender(string $accountNumber): self
    {
        $sender = $this->xml->addChild('SenderInfo');
        $sender->addChild('AccountNumber', $accountNumber);

        return $this;
    }

    public function setReceiver(
        string $bankCode,
        string $accountNumber,
        string $beneficiaryName
    ): self {
        $receiver = $this->xml->addChild('ReceiverInfo');
        $receiver->addChild('BankCode', $bankCode);
        $receiver->addChild('AccountNumber', $accountNumber);
        $receiver->addChild('BeneficiaryName', $beneficiaryName);

        return $this;
    }

    public function setNotes(array $notes): self
    {
        if (empty($notes)) {
            return $this;
        }

        $notesNode = $this->xml->addChild('Notes');
        foreach ($notes as $note) {
            $notesNode->addChild('Note', $note);
        }

        return $this;
    }

    public function setPaymentType(int $type): self
    {
        if ($type !== 99) {
            $this->xml->addChild('PaymentType', (string)$type);
        }

        return $this;
    }

    public function setChargeDetails(string $charge): self
    {
        if ($charge !== 'SHA') {
            $this->xml->addChild('ChargeDetails', $charge);
        }

        return $this;
    }

    public function build(): string
    {
        return $this->xml->asXML();
    }
}
