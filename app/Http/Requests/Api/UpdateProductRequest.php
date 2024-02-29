<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
        if (request()->method() === 'PUT') {
            return [
                'name' => ['required', 'max:255'],
                'cost' => ['required', 'integer', 'min:0'],
                'price' => ['required', 'integer', 'min:0'],
                'unit' => ['required', 'max:255'],
                'description' => ['required'],
                'feature_image' => ['required', 'max:255'],
                'categories' => ['sometimes', 'array'],
                'categories.*' => ['required', Rule::exists('categories', 'id')],
            ];
        } else {
            return [
                'name' => ['sometimes', 'max:255'],
                'cost' => ['sometimes', 'integer', 'min:0'],
                'price' => ['sometimes', 'integer', 'min:0'],
                'unit' => ['sometimes', 'max:255'],
                'description' => ['sometimes'],
                'feature_image' => ['sometimes', 'max:255'],
                'categories' => ['sometimes', 'array'],
                'categories.*' => ['required', Rule::exists('categories', 'id')],
            ];
        }
    }
}
