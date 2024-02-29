<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        return $user !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'max:255'],
            'cost' => ['required', 'integer', 'min:0'],
            'price' => ['required', 'integer', 'min:0'],
            'unit' => ['required', 'max:255'],
            'description' => ['required'],
            'feature_image' => ['required', 'max:255']
        ];
    }
}
