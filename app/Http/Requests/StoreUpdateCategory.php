<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateCategory extends FormRequest
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
        // $id = $this->id;
        // $id = $this->category;
        // 1 / 2 / 3 => categories/10/edit
        // $id = $this->segment(3);

        $rules = [
            // "title" => ["required", "min:3", "max:60", "unique:categories,title,{$id},id"],
            "title" => ["required", "min:3", "max:60", Rule::unique('categories')->ignore($this->category ?? $this->id)],
            "description" => ["max:2000"]
        ];

        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            // $rules["url"] = ["required", "min:3", "max:60", "unique:categories,url,{$id},id"];
            $rules["url"] = ["required", "min:3", "max:60", "string", Rule::unique('products')->ignore($this->category ?? $this->id)];
        }

        return $rules;
    }
}
