<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $id = $this->id;

        return [
            "name" => ["required", "min:3", "max:60", "unique:products,name,{$id},id"],
            "url" => ["required", "min:3", "max:60", "unique:products,url,{$id},id"],
            "price" => ["required", "decimal:2,4"],
            "description" => ["max:9000"],
            "category_id" => ["required", "exists:categories,id"]
        ];
    }
}
