<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FusionnerPanierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'items' => ['present', 'array'],
            'items.*.service_id' => ['required', 'integer', 'exists:services,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
