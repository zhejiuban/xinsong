<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
                    'start_at' => 'bail|required',
                    'end_at' => 'bail|required|after_or_equal:start_at',
                    'leader' => 'bail|required',
                    'content' => 'bail|required',
                    'is_need_plan' => 'bail|required',
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
            'start_at.required'=>'请输入开始时间',
            'end_at.required'=>'请输入截止时间',
            'end_at.after'=>'截止时间要大于开始时间',
            'leader.required'=>'请输入任务负责人',
            'content.required'=>'请输入任务内容',
            'is_need_plan.required'=>'请选择是否需要上传计划',
        ];
    }
}
