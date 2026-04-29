<?php

namespace App\Http\Requests\Author;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateAuthorRequest extends FormRequest
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

    protected function prepareForValidation():void
    {
        if(!$this->has('code')){
            $this->merge(['code' => $this->generateAuthorCode()]);
        }
    }


    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:20',
            'last_name' => 'required|string|max:20',
            'full_name' => 'required|string|max:40',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:authors',
            'dob' => 'nullable|string',
            'age' => 'nullable|integer',
            'code' => 'required|string|max:20|unique:authors',
            'status' => 'required|in:active,inactive',
        ];
    }

    protected function generateAuthorCode()
    {   
        $prefix = 'ATH';
        $date = now()->format('dmy');
        $randomLetter = strtoupper(Str::random(4));  
        return $prefix . '-' . $date . '-' . $randomLetter;
    } 

}
