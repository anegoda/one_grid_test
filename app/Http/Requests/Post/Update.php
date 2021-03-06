<?php

namespace App\Http\Requests\Post;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class Update extends FormRequest
{
    public function authorize(): bool
    {
        if (empty($this->id)) {
            return false;
        }

        return auth()->user()->posts()->where('id', $this->id)->exists();
    }

    public function rules(): array
    {
        return [
            'id' => ['required'],
            'title' => ['required', 'max:255', 'unique:posts,title,' . $this->id],
            'text' => ['required']
        ];
    }
}
