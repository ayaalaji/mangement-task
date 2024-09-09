<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role == 'admin');
    }
    public function prepareForValidation()
    {
        $this->merge([
            'first_name' =>ucwords($this->input('first_name')),
            'last_name' =>ucwords($this->input('last_name'))
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ];
    }
    protected function passedValidation()
    {
        /**
         * merge fisrt name and last name to became full name
        */
        $this->merge([
            'User Name' =>$this->input('first_name') . ' ' . $this->input('last_name')
        ]);
    }
}
