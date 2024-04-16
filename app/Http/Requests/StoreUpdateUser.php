<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        $id = $this->id;

        $rules =  [
            "name" => ["required", "min:3", "max:60"],
            "email" => ["required", "email", "unique:users,email,{$id},id"],
            "password" => ["required", "min:3", "max:15"]
        ];

        if ($this->isMethod('PUT') && $this->isMethod('PATCH')) {
            $rules["password"] = ["max:15"];
        }

        return $rules;
    }
}
