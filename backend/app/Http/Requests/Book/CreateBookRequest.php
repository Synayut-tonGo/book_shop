<?php

namespace App\Http\Requests\Book;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CreateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $user = User::auth()->user();

        // if (!$user) {
        //     return false;
        // }

        return $this->user()->hasPermission('create-books');

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    protected function prepareForValidation():void
    {
        if(!$this->has('code')){
            $this->merge(['code' => $this->generateBookCode()]);
        }
    }
    

    public function rules(): array
    {
        return [
            'code' => 'required|string|unique:books',
            'author_id' => 'required|exists:authors,author_id',
            'category_id' => 'required|exists:categories,category_id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:300',
            'quantity' => 'required|numeric|min:0|max:9999.99',
            'discount' => 'nullable|numeric|min:0|max:0.99',
            'status' => 'required|in:out_stock,comming_soon,active,inactive'                      
        ];
    }

    protected function generateBookCode(){

        $prefix = 'BK';
        $date = now()->format('dmy');
        $randomLetter= strtoupper(Str::random(4));

        return $prefix . '-'.$date.'-'.$randomLetter;

    }
}
