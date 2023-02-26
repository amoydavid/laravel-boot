<?php

namespace App\Http\Requests\Api\User;

use Illuminate\Foundation\Http\FormRequest;

class FetchWxInfoRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'encryptedData' => 'required',
            'iv' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'encryptedData' => '加密信息',
            'iv' => '解密向量',
        ];
    }
}
