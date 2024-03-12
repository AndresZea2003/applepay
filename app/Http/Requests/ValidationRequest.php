<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ValidationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'validationUrl' => 'required|url'
        ];
    }

    public function validationUrl(): string
    {
        return $this->input('validationUrl');
    }
}
