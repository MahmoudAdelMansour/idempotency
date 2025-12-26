<?php

namespace App\Http\Controllers;

use App\Http\Requests\WebhookRequest;
use App\Jobs\ProcessWebhookJob;
use App\Models\Webhook;

class WebhookController extends Controller
{
    public function receiveWebhook(WebhookRequest $request)
    {
        $validated = $request->validated();
//        I can do it from the Request page but , I prefer to do it here because single responsibilities
        $validated['payload'] = json_encode(trim($request->getContent()));
        $webhook = Webhook::create($validated);
        ProcessWebhookJob::dispatch($webhook);

        return response()->json(['message' => 'Webhook received successfully']);
    }
}
