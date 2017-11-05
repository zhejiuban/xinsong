<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'bail|required',
            'status' => 'bail|required',
        ];
        if($this->department == headquarters('id')){
            unset($rule['status']);
        }
        return $rule;
    }

    public function messages()
    {
        return [
            'name.required' => '请输入分部名称',
            'status.required' => '请选择分部状态',
        ];
    }
}
