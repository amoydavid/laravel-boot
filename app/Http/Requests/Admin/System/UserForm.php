<?php

namespace App\Http\Requests\Admin\System;

class UserForm extends \Illuminate\Foundation\Http\FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'password' => $this->routeIs('*.user.create')?'required':'',
            'role_ids' => 'array',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '姓名',
            'phone' => '手机号',
            'email' => '电子邮箱',
            'password' => '密码',
            'role_ids' => '角色',
        ];
    }
}
