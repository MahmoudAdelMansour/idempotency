<?php

namespace App\Builder;

interface PaymentRequestBuilder
{
    public function setTransferInfo(
        string $reference,
        \DateTimeInterface $date,
        float $amount,
        string $currency
    ): self;

    public function setSender(string $accountNumber): self;

    public function setReceiver(
        string $bankCode,
        string $accountNumber,
        string $beneficiaryName
    ): self;

    public function setNotes(array $notes): self;

    public function setPaymentType(int $type): self;

    public function setChargeDetails(string $charge): self;

    public function build(): string;
}
