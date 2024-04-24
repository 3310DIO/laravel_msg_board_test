<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestReply extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reply' => 'required|max:1500',
            'msg_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'reply.required' => '請輸入回覆內容',
            'reply.max' => '回覆內容不能超過1500字',
            'msg_id.required' => '錯誤',
        ];
    }
}
