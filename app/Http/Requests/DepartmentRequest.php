<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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

    public function rules()
    {
        if($this->method() == 'POST'){
            return $rule = [
                'parent_id' => 'bail|required',
                'name' => 'bail|required',
                'status' => 'bail|required',
            ];
        }

        if($this->method() == 'PUT' || $this->method() == 'PATCH' ){
            $rule = [
                'parent_id' => 'bail|required',
                'name' => 'bail|required',
                'status' => 'bail|required',
            ];
            $rule['parent_id'] =  'bail|required|different:id';
            return $rule;
        }
        return [];
    }
    public function messages()
    {
        return [
            'parent_id.required'=>'请选择上级部门',
            'parent_id.different'=>'上级部门不能为自身',
            'name.required' => '请输入部门名称',
            'status.required' => '请选择部门状态',
        ];
    }
}
