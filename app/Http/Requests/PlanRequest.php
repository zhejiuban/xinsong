<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
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
                    'sort' => 'bail|required|numeric',
                    'content' => 'bail|required',
                    'started_at' => 'bail|required|date',
                    'finished_at' => 'bail|required|date|after_or_equal:started_at',
                    'user_id' => 'bail|required',
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
            'sort.required' => '请输入序号',
            'content.required' => '请输入计划内容',
            'started_at.required' => '请输入计划开始时间',
            'finished_at.required' => '请输入计划完成时间',
            'finished_at.after_or_equal' => '计划完成时间要大于开始时间',
            'user_id.required' => '请选择计划执行人',
        ];
    }
}
