<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MalfunctionRequest extends FormRequest
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
                    'device_id' => 'bail|required',
                    'project_phase_id' => 'bail|required',
                    'content' => 'bail|required',
                    'reason' => 'bail|required',
                    'handled_at' => 'bail|required',
                    'result' => 'bail|required',
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
            'device_id.required' => '请选择设备类型',
            'project_phase_id.required' => '请选择故障来自阶段',
            'content.required' => '请输入故障现象',
            'reason.required' => '请输入故障原因',
            'handled_at.required' => '请选择故障处理时间',
            'result.required' => '请填写故障处理结果',
        ];
    }
}
