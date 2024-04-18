<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateProduct extends FormRequest
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
        $rules =  [
            "name" => ["required", "min:3", "max:60", Rule::unique('products')->ignore($this->product ?? $this->id)],
            "price" => ["required", "decimal:2,4"],
            "description" => ["max:9000"],
            "category_id" => ["required", "exists:categories,id"]
        ];

        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules["url"] = ["required", "string", Rule::unique('products')->ignore($this->product ?? $this->id)];
        }

        return $rules;
    }
}
