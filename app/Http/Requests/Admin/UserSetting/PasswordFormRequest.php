<?php

namespace App\Http\Requests\Admin\UserSetting;

class PasswordFormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules()
    {
        return [
            'old_password' => 'required|current_password:admin',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => '旧密码',
            'new_password' => '新密码',
            'conform_password' => '确认密码',
        ];
    }
}
