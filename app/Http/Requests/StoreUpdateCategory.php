<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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

        // 1 / 2 / 3  
        // categories/10/edit
        $id = $this->segment(3);        

        return [
            "title" => ["required", "min:3", "max:60", "unique:categories,title,{$id},id"],
            "url" => ["required", "min:3", "max:60", "unique:categories,url,{$id},id"],
            "description" => ["max:2000"]
        ];
    }
}
