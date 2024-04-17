<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUpdateUser extends FormRequest
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
            "name" => ["required", "min:3", "max:60"],
            "email" => ["required", "string", "email", Rule::unique('users')->ignore($this->user ?? $this->id)],
            "password" => ["required", "min:3", "max:15"]
        ];

        if ($this->method() === 'PUT' || $this->method() === 'PATCH') {
            $rules["password"] = ["min:3", "max:15"];
        }

        return $rules;
    }
}
