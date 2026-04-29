<?php

namespace App\Http\Requests\Book;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return User::auth()->user()->hasPermission('edit-books');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'author_id' => 'sometimes|exists:authors,author_id',
            'category_id' => 'sometimes|exists:categories,category_id',
            'name' => 'sometimes|string|max:100',
            'quantity' => 'sometimes|numeric|min:0|max:9999.99',
            'discount' => 'sometimes|numeric|min:0|max:0.99',
            'status' => 'sometimes|in:out_stock,comming_soon,active,inactive'
        ];
    }
}
