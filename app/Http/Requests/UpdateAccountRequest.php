<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('account')->user_id === $this->user()->id;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'type' => 'sometimes|string',
        ];
    }
}