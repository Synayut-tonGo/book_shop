<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //return Auth::auth()->user()->hasRole('admin' | 'super_admin');
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */


    protected function prepareForValidation()
    {
        if(!$this->has('code')){
            $this->merge(['code' => $this->generateCategoryCode()]);
        }
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:40',
            'code' => 'required|string|unique:categories',
            'status' => 'required|in:active,inactive'
        ];
    }

    protected function generateCategoryCode(){

        $prefix = 'CAT';
        $date = now()->format('dmy');
        $randomLetter= strtoupper(Str::random(4));

        return $prefix . '-'.$date.'-'.$randomLetter;

    }
}
