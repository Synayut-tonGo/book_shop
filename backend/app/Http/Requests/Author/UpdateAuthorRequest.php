<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateAuthorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::auth()->user()->hasRole('admin' | 'super_admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'sometimes|string|max:20',
            'last_name' => 'sometimes|string|max:20',
            'full_name' => 'sometimes|string|max:40',
            'phone' => 'sometimes|string|max:20',
            'email' => 'sometimes|email|unique:authors',
            'dob' => 'sometimes|string',
            'age' => 'sometimes|integer',
            'status' => 'sometimes|in:active.inactive',
        ];
    }
}
