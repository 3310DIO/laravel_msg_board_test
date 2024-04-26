<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Member;
use Illuminate\Validation\Rule;

class RequestMember extends FormRequest
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
        $member_list = Member::pluck('user_account')->toArray();
        return [
            'user_account' => ['required', 'between:8,20', 'alpha_num', Rule::notIn($member_list)],
            'user_name' => 'required|between:2,20',
            'user_password' => 'required|regex:/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/',
            'user_password_check' => 'required|same:user_password',
        ];
    }
    public function messages()
    {
        return [
            'user_account.required' => '請輸入帳號',
            'user_account.between' => '帳號需在8~20字間',
            'user_account.alpha_num' => '帳號需由字母或數字構成',
            'user_account.not_in' => '帳號已存在，請重新輸入',
            'user_name.required' => '請輸入暱稱',
            'user_name.between' => '暱稱需在2~20字間',
            'user_password.required' => '請輸入密碼',
            'user_password.regex' => '密碼須在8~25字之間，並包含大小寫字母及特殊符號',
            'user_password_check.required' => '請輸入確認密碼',
            'user_password_check.same' => '密碼與確認密碼不同',
        ];
    }
}
