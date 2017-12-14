<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
                    'title' => 'bail|required',
                    'no' => 'bail|required|unique:projects',
                    'department_id' => 'bail|required',
                    'leader' => 'bail|required',
                    'agent' => 'bail|required',
                    'project_user' => 'bail|required',
                    'customers' => 'bail|required',
                    'customers_tel' => 'bail|required',
                    'customers_address' => 'bail|required',
                    'device_project.*.device_id' => 'bail|required|distinct',
                    'device_project.*.number' => 'bail|required',
                    'project_phases.*.name' => 'bail|required',
                    'project_phases.*.started_at' => 'bail|required',
                    'project_phases.*.finished_at' => 'bail|required|after_or_equal:project_phases.*.started_at',
                ];
            }
            case 'PUT':
            case 'PATCH': {
                return [
                    'title' => 'bail|required',
                    'no' => 'bail|required|unique:projects,no,'.request('project'),
                    'department_id' => 'bail|required',
                    'leader' => 'bail|required',
                    'agent' => 'bail|required',
                    'project_user' => 'bail|required',
                    'customers' => 'bail|required',
                    'customers_tel' => 'bail|required',
                    'customers_address' => 'bail|required',
                    'device_project.*.device_id' => 'bail|required|distinct',
                    'device_project.*.number' => 'bail|required',
                    'project_phases.*.name' => 'bail|required',
                    'project_phases.*.started_at' => 'bail|required',
                    'project_phases.*.finished_at' => 'bail|required|after_or_equal:project_phases.*.started_at',
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
            'title.required' => '请输入项目名称',
            'no.required' => '请输入项目编号',
            'no.unique' => '项目编号已存在',
            'department_id.required' => '请输入项目所属部门',
            'leader.required' => '请输入项目负责人',
            'agent.required' => '请输入项目现场代理负责人',
            'project_user.required' => '请输入项目参与人',
            'customers.required' => '请输入项目客户对接人',
            'customers_tel.required' => '请输入项目客户对接人电话',
            'customers_address.required' => '请输入项目客户地址',
            'device_project.*.device_id.required' => '请选择设备类型',
            'device_project.*.number.required' => '请输入设备数量',
            'device_project.*.device_id.distinct' =>'设备类型不能重复',
            'project_phases.*.name.required' => '请输入各建设阶段名称',
            'project_phases.*.started_at.required' => '请输入各建设阶段开始日期',
            'project_phases.*.finished_at.required' => '请输入各建设阶段结束日期',
            'project_phases.*.finished_at.after_or_equal' => '各建设阶段结束日期要不小于开始日期',
        ];
    }
}
