<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Support\Facades\DB;
// use Illuminate\View\View;
use App\Models\Member;
use Illuminate\Validation\Rule;
use App\Http\Requests\RequestMember;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('member/login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('member/register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RequestMember $request)
    {
        $member = new Member();
        $user_account = $request->input("user_account");
        $user_name = $request->input("user_name");
        $user_password = $request->input("user_password");
        
        $password_hash = password_hash($user_password, PASSWORD_DEFAULT);
        $member->user_account = $user_account;
        $member->user_name = $user_name;
        $member->user_password = $password_hash;
        $member->save();
        session()->put('account', $user_account);
        session()->put('name', $user_name);
        $message = "註冊成功";

        return redirect()->route('msg.index')->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_account)
    {
        $account = Member::findMember($user_account);
        if($account == null){
            $message = "錯誤";

            return redirect()->route('msg.index')->with('error', $message);
        }
        
        $model = array();

        $view = 'member/space';
        $img = Member::memberSpace($user_account);
        $model['user_spaces'] = $img;
        $user_introduce = Member::memberIntroduce($user_account);
        $model['account'] = $user_introduce;
        $model['img_id'] = 0;
        // dd($account);
        return view($view, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user_account = session()->get('account');
        $member = Member::findMember($user_account);
        if($member == null){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }
        $user_introduce = Member::memberIntroduce($user_account);
        $model['account'] = $user_introduce;
        return view('member/edit_user', $model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $account = session()->get('account');
        $user_name_old = session()->get('name');
        $member = Member::findMember($account);
        // dd($sql);
        if($member == null){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }
        $member_data = Member::find($member->user_id);

        $user_name = $request->input("user_name", '');
        $user_password_old = $request->input("user_password_old", '');
        $user_password_new = $request->input("user_password_new", '');
        $user_password_check = $request->input("user_password_check", '');
        $user_introduce = $request->input("user_introduce", '');
        $user_color = $request->input("user_color", '');
        $user_introduce_old = $member_data->user_introduce;
        $user_color_old = $member_data->user_color;
        // dd($request->all());
        if(($user_name == '' || $user_name == $user_name_old) && $user_password_old == '' && $user_password_new == '' && $user_password_check == '' && ($user_introduce == '' || $user_introduce == $user_introduce_old) && ($user_color == '' || $user_color == $user_color_old)){
            $message = "請輸入修改內容";

            return redirect()->route('member.edit', session()->get('account'))->withInput()->with('error', $message);
        }

        if($user_name != '' && $user_name != $user_name_old){
            $request->validate([
                'user_name' => 'between:2,20',
            ],[
                'user_name.between' => '暱稱需在2~20字間',
            ]);
            $member_data->user_name = $user_name;
            session()->put('name', $user_name);
        }
        if($user_password_old != '' && $user_password_new != '' && $user_password_check != ''){
            if(!password_verify($user_password_old, $member_data->user_password)){
                $message = '舊密碼錯誤，請重新輸入';
                return redirect()->route('member.edit', session()->get('account'))->withInput()->with('error', $message);
            }else{
                $request->validate([
                    'user_password_new' => 'regex:/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/',
                    'user_password_check' => 'same:user_password_new',
                ],[
                    'user_password_new.regex' => '密碼須在8~25字之間，並包含大小寫字母及特殊符號',
                    'user_password_check.regex' => '密碼與確認密碼不同',
                ]);
            }

            $password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);
            $member_data->user_password = $password_hash;
        }elseif($user_password_old != '' || $user_password_new != '' || $user_password_check != ''){
            $message = '請輸入完整修改內容';
            return redirect()->route('member.edit', session()->get('account'))->withInput()->with('error', $message);
        }
        // dd($user_color);
        if($user_introduce != '' && $user_introduce != $user_introduce_old){
            $request->validate([
                'user_introduce' => 'max:500',
            ],[
                'user_introduce.max' => '簡介需在500字之間',
            ]);
            $member_data->user_introduce = $user_introduce;
        }

        if($user_color != '' && $user_color != $user_color_old){
            $request->validate([
                'user_color' => 'hex_color:#RRGGBB',
            ],[
                'user_color.hex_color' => '不符合色碼標準',
            ]);
            $member_data->user_color = $user_color;
        }

        $message = "修改成功";
        $member_data->save();
        return redirect()->route('member.show', session()->get('account'))->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
    public function login(Request $request)
    {
        $member = Member::pluck('user_account')->toArray();
        $request->validate([
            'user_account' => ['required', Rule::in($member)],
            'user_password' => 'required',
        ],[
            'user_account.required' => '請輸入帳號',
            'user_account.in' => '帳號不存在，請重新輸入',
            'user_password.required' => '請輸入密碼',
        ]);
        $user_account = $request->input("user_account", '');
        $user_password = $request->input("user_password", '');
        $account = Member::findMember($user_account);
        // dd($sql);
        
        if(!(password_verify($user_password, $account->user_password))){
            $message = '密碼錯誤，請重新輸入';
            return redirect()->route('member.index')->withInput()->with('error', $message);
        }else{
            session()->put('account', $account->user_account);
            session()->put('name', $account->user_name);
            $message = "登入成功";

            return redirect()->route('msg.index')->with('message', $message);
        }
        
    }
    public function logout(Request $request)
    {
        session()->flush();
        // dd(session());
        $message = "登出成功";

        return redirect()->route('msg.index')->with('message', $message);
    }
}
