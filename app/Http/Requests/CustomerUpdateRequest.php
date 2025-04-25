<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|required|string|max:255',
            'age' => 'sometimes|required|integer|min:18|max:60',
            'region' => 'sometimes|required|string|max:255',
            'income' => 'sometimes|required|integer|min:0',
            'score' => 'sometimes|required|integer|min:0',
            'pin' => 'sometimes|required|string|max:255|unique:customers,pin,' . $this->route('customer'),
            'email' => 'sometimes|required|email|max:255',
            'phone' => 'sometimes|required|string|max:15',
        ];
    }
}
