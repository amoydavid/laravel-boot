<?php

namespace App\Http\Requests\Admin\Permission;

use App\Models\Permission;

class FormRequest extends \Illuminate\Foundation\Http\FormRequest
{
    public function authorize()
    {
        return $this->user()->can('create', Permission::class) || $this->user()->can('update', Permission::class);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'path' => 'required',
            'title' => 'required',
//            'component' => 'string',
            'show_parent' => 'boolean',
//            'frame_src' => 'string',
//            'icon' => 'string',
            //'parent_id' => 'int',
            'type' => 'in:0,1'
        ];
    }

    public function attributes()
    {
        return [
            'name' => '组件名',
            'path' => '访问路径',
            'title' => '标题',
            'component' => '组件路径',
            'show_parent' => '显示上级组件',
            'frame_src' => 'iframe页面URL',
            'icon' => '图标',
            'parent_id' => '上级',
            'type' => '权限类型'
        ];
    }
}
