<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateUserRequest extends FormRequest
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
        $userId = $this->route('user') ?? $this->input('user');
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => 'nullable|string|min:8',
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
    public function FaildValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors()->all();
        $response = response()->json([
           'status' => 'error',
           'message' => 'There was an error with your submission. Please review the errors below:',
           'errors' => $errors
        ], 422);
        throw new HttpResponseException($response);

    }
    public function messages()
    {
        return [
            'required' => 'حقل :attribute هو حقل إجباري.',
            'string' => 'حقل :attribute يجب أن يكون قيمة نصية.',
            'in' => 'حقل :attribute يجب أن يكون واحداً من القيم التالية: :values.',
            'min' => 'حقل :attribute يجب أن يحتوي على الأقل :min حرفاً.',
            'max' => 'حقل :attribute لا يمكن أن يتجاوز :max حرفاً.',
            'exists' => 'حقل:attribute يجب ان يكون موجود في جدول المستخدمين'
        ];
    }
}
