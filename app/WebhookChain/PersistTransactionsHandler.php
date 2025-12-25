<?php

namespace App\WebhookChain;

use App\Models\Transaction;
use App\Models\Webhook;
use App\WebhookChain\WebhookHandler;

class PersistTransactionsHandler extends WebhookHandler
{

    public function handle(Webhook $webhook, array $context = []): array
    {
        echo "test";
        $transactions = $context['transactions'] ?? [];

        foreach ($transactions as $transactionData) {
           Transaction::updateOrCreate(
               [
               'bank_name' => $webhook->bank_name,
               'bank_reference' => $transactionData['reference']
               ],
               [
                   'client_id' => $transactionData['client_id'] ?? null,
                   'amount' => $transactionData['amount'],
                   'currency' => $transactionData['currency'] ?? '$',
                   'date' => $transactionData['date'],
                   'raw_payload' => json_encode($transactionData),
               ]
           );
        }
        return $this->handleNext($webhook, $context);

    }
}
