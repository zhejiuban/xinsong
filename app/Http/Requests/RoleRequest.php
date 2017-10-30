<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                return [];
            }
            case 'POST': {
                return [
                    'name' => 'bail|required|max:100|unique:roles,name'
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'name' => 'bail|required|max:40|unique:roles,name,' . request('group')
                ];
            }
            default:
                return [];
                break;
        }
    }

    public function messages()
    {
        return [
            'name.required' => '请输入角色名称',
            'name.max' => '不能大于100个字符',
            'name.unique' => '角色已存在'
        ];
    }
}
