<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'max:255', 'unique:posts,title'],
            'text' => ['required']
        ];
    }
}
