<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WebhookRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'bank_name' => $this->normalizeBankName($this->route('bank_name')),


        ]);
    }

    public function rules(): array
    {
        return [
            'bank_name' => ['required', 'string'],
        ];
    }

    private function normalizeBankName(?string $name): ?string
    {
        if (!$name) return null;

        return strtolower(str_replace(' ', '_', trim($name)));
    }

    public function authorize(): bool
    {
        return true;
    }
}
