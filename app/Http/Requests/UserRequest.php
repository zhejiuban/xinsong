<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        switch ($this->method()){
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                    'username'=>'bail|required|min:4|max:40|unique:users,username',
                    'password'=>'bail|required|min:6',
                    'name'=>'bail|required',
                    'department_id'=>'bail|required',
                    'email'=>'bail|required|email|unique:users,email',
                    'tel'=>'bail|required|unique:users,tel',
                    'status'=>'bail|required',
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'username'=>'bail|required|min:4|max:40|unique:users,username,'.request('user'),
                    'password'=>'bail|nullable|min:6',
                    'name'=>'bail|required',
                    'department_id'=>'bail|required',
                    'email'=>'bail|required|email|unique:users,email,'.request('user'),
                    'tel'=>'bail|required|unique:users,tel,'.request('user'),
                    'status'=>'bail|required',
                ];
            }
            default:break;
        }
    }

    public function messages()
    {
        return [
            'username.required'=>'请输入用户名',
            'username.min'=>'用户名不能少于4个字符',
            'username.max'=>'用户名不能大于40个字符',
            'username.unique'=>'用户名已存在',
            'password.required'=>'请输入登录密码',
            'password.min'=>'密码不能小于6个字符',
            'name.required'=>'请输入姓名',
            'email.required'=>'请输入邮箱',
            'email.email'=>'请输入正确格式的邮箱',
            'email.unique'=>'邮箱已存在',
            'tel.required'=>'请输入手机号',
            'tel.unique'=>'手机号已存在',
            'department_id.required'=>'请选择所属部门'
        ];
    }
}
