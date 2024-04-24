<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestMsg extends FormRequest
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
            'subtitle' => 'required|max:16',
            'title' => 'required|max:256',
            'content' => 'required|max:5000',
        ];
    }
    public function messages()
    {
        return [
            'subtitle.required' => '錯誤',
            'subtitle.max' => '錯誤',
            'title.required' => '請輸入標題',
            'title.max' => '標題不能超過256字',
            'content.required' => '請輸入內容',
            'content.max' => '內容不能超過5000字',
        ];
    }
}
