<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductFaultRequest extends FormRequest
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
            case 'POST':
            case 'PUT':
            case 'PATCH': {
                return [
                    'project_id' => 'bail|required',
                    'car_no' => 'bail|required',
                    'fault_cause_id' => 'bail|required',
                    'occurrenced_at' => 'bail|required',
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
            'project_id.required' => '请选择所属项目',
            'car_no.required' => '请输入小车编号',
            'fault_cause_id.required' => '请选择故障现象',
            'occurrenced_at.required' => '请选择故障发生时间',
        ];
    }
}
