<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'age' => 'required|integer|min:18|max:60',
            'region' => 'required|string|max:255',
            'income' => 'required|integer|min:0',
            'score' => 'required|integer|min:0',
            'pin' => 'required|string|max:255|unique:customers,pin',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
        ];
    }
}
