<?php

namespace App\Http\Requests\Password_admin;

use Illuminate\Foundation\Http\FormRequest;

class FormRequestPasswordAdmin extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password_old' => 'required',
            'password_new' => 'required',
            'password_confirm' => 'required|same:password_new',
        ];
    }
    public function messages(): array
    {
        return [
            'password_old.required' => 'Vui lòng nhập mật khẩu cũ!',
            'password_new.required' => 'Vui lòng nhập mật khẩu mới!',
            'password_confirm.required' => 'Vui lòng nhập mật khẩu xác nhận!',
            'password_confirm.same' => 'Mật khẩu xác nhận phải giống mật khẩu mới!',
        ];
    }
}
