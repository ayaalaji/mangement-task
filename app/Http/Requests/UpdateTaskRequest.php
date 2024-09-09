<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [];
        $task = $this->route('task');
        if (Auth::user()->role == 'admin') {
            $rules = [
                'title' => 'nullable|string|min:3|max:100',
                'description' => 'nullable|string|min:5|max:255',
                'priority' => 'nullable|string|in:important,moderate_importance,normal',
                'assigned_to' => 'nullable|string',
                'user_id' => 'nullable|exists:users,id',
            ];
        } elseif (Auth::user()->role == 'manager') 
        {
            // Manager logic
        if ($task) {
            // Check if the fields are filled in the task
            $assignedToFilled = !is_null($task->assigned_to);
            $userIdFilled = !is_null($task->user_id);
            $addedBy =$task->added_by;

            if ($addedBy == 'admin') {
                if ($assignedToFilled && $userIdFilled) {
                    // Both fields are filled by admin, so manager cannot update either
                    $rules = [
                        'assigned_to' => 'prohibited',
                        'user_id' => 'prohibited',
                    ];
                } elseif ($assignedToFilled) {
                    // assigned_to is filled, manager can update only user_id
                    $rules = [
                        'assigned_to' => 'prohibited',
                        'user_id' => 'nullable|integer|exists:users,id',
                    ];
                } elseif ($userIdFilled) {
                    // user_id is filled, manager can update only assigned_to
                    $rules = [
                        'assigned_to' => 'nullable|string|min:3|max:100',
                        'user_id' => 'prohibited',
                    ];
                } else {
                    // Neither field is filled, manager can fill both fields
                    $rules = [
                        'assigned_to' => 'nullable|string|min:3|max:100',
                        'user_id' => 'nullable|integer|exists:users,id',
                    ];
                }
            } elseif ($addedBy == 'manager') {
                // Manager can update both fields
                $rules = [
                    'assigned_to' => 'nullable|string|min:3|max:100',
                    'user_id' => 'nullable|integer|exists:users,id',
                ];
            }
        }
    } elseif (Auth::user()->role == 'user') {
            $rules = [
                'status' => 'nullable|string|in:in_progress,completed',
                'due_date' => 'nullable|date_format:d-m-Y H:i',
            ];
        }

        return $rules;
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
            'status' => 'حالة المهمة',
            'due_date' => 'موعد تسليم المهمة',
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
            'exists' => 'حقل:attribute يجب ان يكون موجود في جدول المستخدمين',
            // Custom message when 'assigned_to' field is prohibited
           'assigned_to.prohibited' => 'حقل :attribute قد تم ملؤه بالفعل ولا يمكن تعديله.',
            // Custom message when 'user_id' field is prohibited
            'user_id.prohibited' => 'حقل :attribute قد تم ملؤه بالفعل ولا يمكن تعديله.',
        ];
    }

}

