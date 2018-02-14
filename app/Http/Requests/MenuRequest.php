<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
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
        $rule = [
            'parent_id' => 'bail|required',
            'title' => 'bail|required',
            'url' => request('menu') ?
                'bail|required|unique:menus,url,'.request('menu')
                : 'bail|required|unique:menus,url',
//                    'gurad_name'=> 'bail|required',
            'hide' => 'bail|required',
            'status' => 'bail|required',
            'target' => 'bail|required',
        ];
        if($this->method() == 'PUT' || $this->method() == 'PATCH' ){
            $rule['parent_id'] =  'bail|required|different:id';
        }
        return $rule;
    }
    public function messages()
    {
        return [
            'parent_id.required'=>'请选择父级菜单',
            'parent_id.different'=>'上级菜单不能为自身',
            'title.required' => '请输入菜单名称',
            'url.required' => '请输入访问地址',
            'url.unique' => '访问地址已存在',
            'hide.required' => '请选择是否隐藏',
            'target.required' => '请选择是窗口打开方式',
            'status.required' => '请选择菜单状态',
        ];
    }
}
