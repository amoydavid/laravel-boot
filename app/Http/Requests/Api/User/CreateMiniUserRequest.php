<?php

namespace App\Http\Requests\Api\User;

use App\Models\Permission;
use Illuminate\Foundation\Http\FormRequest;

class CreateMiniUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
//    public function authorize()
//    {
//        return $this->user()->can('create', Permission::class);
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'code' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'code' => '登录码'
        ];
    }

    public function messages()
    {
        return [
            'code.required' => '登录码未提供'
        ];
    }
}
