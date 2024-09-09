<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && (Auth::user()->role == 'admin' || Auth::user()->role == 'manager');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:100',
            'description' => 'required|string|min:5|max:255',
            'priority' => 'required|string|in:important,moderate_importance,normal',
            'assigned_to' => 'nullable|string|min:3|max:100',
            'user_id' => 'nullable|integer|exists:users,id',
            
            
        ];
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

    public function attributes()
    {
        $userName = 'الاسم غير موجود';
    
        if ($this->has('user_id')) {
            $userId = $this->input('user_id');
            $user =User::find($userId);
            $userName = $user ? $user->full_name : $userName;
        }
        return [
            'title' =>'عنوان المهمة',
            'description' => 'وصف المهمة',
            'priority' => 'أولوية المهمة',
            'assigned_to' => 'تعيين مهمة ما لمستخدم معين',
            'user_id' => $userName,
        ];
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
