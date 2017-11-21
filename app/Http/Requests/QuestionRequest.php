<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
                    'title' => 'bail|required',
                    'question_category_id' => 'bail|required',
//                    'project_id' => 'bail|required',
                    'receive_user_id' => 'bail|required',
                    'content' => 'bail|required',
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
            'title.required' => '请输入问题标题',
            'question_category_id.required' => '请选择所属版块',
            'receive_user_id.required' => '请输入回复人',
            'content.required' => '请输入问题详情',
        ];
    }
}
