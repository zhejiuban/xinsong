<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DynamicRequest extends FormRequest
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
                $rule = [
//                    'onsite_user' => 'bail|required',
                    'content' => 'bail|required',
                ];
                if($this->project_phases){
                    $rule['project_phases.*.name' ] = 'bail|required';
                    $rule['project_phases.*.started_at' ] = 'bail|required';
                    $rule['project_phases.*.finished_at' ] = 'bail|required|after_or_equal:project_phases.*.started_at';
                }
                return $rule;
            }
            default:
                return [];
                break;
        }
    }
    public function messages()
    {
        return [
            'onsite_user.required'=>'请填写现场人员（第三方配合人员请标注）',
            'content.required'=>'请输入日志内容',
            'project_phases.*.name.required' => '请输入各建设阶段名称',
            'project_phases.*.started_at.required' => '请输入各建设阶段开始日期',
            'project_phases.*.finished_at.required' => '请输入各建设阶段结束日期',
            'project_phases.*.finished_at.after_or_equal' => '各建设阶段结束日期要不小于开始日期',
        ];
    }
}
