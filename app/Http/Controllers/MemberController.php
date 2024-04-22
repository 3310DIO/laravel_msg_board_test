<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\Member;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user['user'] = 1;
        return View('member/loginRegister', $user);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user['user'] = 0;
        return View('member/loginRegister', $user);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'user_account' => 'required|between:8,20|alpha_num',
            'user_name' => 'required|between:2,20',
            'user_password' => 'required|regex:/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/',
            'user_password_check' => 'required|same:user_password',
        ],[
            'user_account.required' => '請輸入帳號',
            'user_account.between' => '帳號需在8~20字間',
            'user_account.alpha_num' => '帳號需由字母或數字構成',
            'user_name.required' => '請輸入暱稱',
            'user_name.between' => '暱稱需在2~20字間',
            'user_password.required' => '請輸入密碼',
            'user_password.regex' => '密碼須在8~25字之間，並包含大小寫字母及特殊符號',
            'user_password_check.required' => '請輸入確認密碼',
            'user_password_check.same' => '密碼與確認密碼不同',
        ]);

        $member = new Member();
        $user_account = $request->input("user_account");
        $user_name = $request->input("user_name");
        $user_password = $request->input("user_password");
        // $user_password_check = $request->input("user_password_check");
        // dd($msg);
        // if($user_password != $user_password_check){
        //     $message = '密碼及確認密碼需相同';
        //     return redirect()->route('member.create')->with('error', $message);
        // }else{
        //     if($user_account == '' || $user_name == '' || $user_password == ''){
        //         $message = '請輸入內容';
        //         return redirect()->route('member.create')->with('error', $message);
        //     }else{
        //         if(!(preg_match('/^[a-zA-Z0-9]{8,20}$/', $user_account) && preg_match('/^.{2,20}$/', $user_name))){
        //             $message = '帳號需在8~20字間且為英文或數字，暱稱需在2~20字間';
        //             return redirect()->route('member.create')->with('error', $message);
        //         }else{
        //             if(!(preg_match('/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/', $user_password))){
        //                 $message = '密碼需在8~25字間，且密碼須包含英文大小寫、數字及特殊符號';
        //                 return redirect()->route('member.create')->with('error', $message);
        //             }else{
        $sql = Member::find_member($user_account);
        if($sql != null && $sql->user_account != ''){
            $message = '該帳號已存在';
            return redirect()->route('member.index')->with('message', $message);
        }else{ // 將輸入的密碼加密後與帳號跟暱稱從入資料庫
            $password_hash = password_hash($user_password, PASSWORD_DEFAULT);
            $member->user_account = $user_account;
            $member->user_name = $user_name;
            $member->user_password = $password_hash;
            $member->save();
            session()->put('account', $user_account);
            session()->put('name', $user_name);
            // session_start();
            // $_SESSION["user_account"] = $user_account;
            // $_SESSION["user_name"] = $user_name;
            $message = "註冊成功";

            return redirect()->route('msg.index')->with('message', $message);
        }
        //             }
        //         }
        //     }
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $user_account)
    {
        $account = Member::find_member($user_account);
        if($account == null){
            $message = "錯誤";

            return redirect()->route('msg.index')->with('error', $message);
        }
        
        $model = array();

        $view = 'member/space';
        $img = Member::member_space($user_account);
        $model['user_spaces'] = $img;
        $user_introduce = Member::member_introduce($user_account);
        $model['account'] = $user_introduce;
        $model['img_id'] = 0;
        // dd($model['user_spaces'][2]);
        // dd($account);
        return View($view, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $user_account)
    {
        $sql = Member::find_member($user_account);
        if($sql == null || session()->get('account') != $user_account){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }
        return View('member/edit_user');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $account)
    {
        // $user_account = session()->get('account');
        // dd($user_account);
        // dd(Route::currentRouteName());
        $user_name_old = session()->get('name');
        $sql = Member::find_member($account);
        // dd($sql);
        if($sql == null || session()->get('account') != $account){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }
        $member_date = Member::find($sql->user_id);


        if(session()->get('page') == 'edit_user'){
            $user_name = $request->input("user_name", '');
            $user_password_old = $request->input("user_password_old", '');
            $user_password_new = $request->input("user_password_new", '');
            $user_password_check = $request->input("user_password_check", '');

            if($user_name != '' && $user_name != $user_name_old){
                $request->validate([
                    'user_name' => 'between:2,20',
                ],[
                    'user_name.between' => '暱稱需在2~20字間',
                ]);
                $member_date->user_name = $user_name;
                // Member::name_update($user_account, $user_name);
                session()->put('name', $user_name);
            }
            if($user_password_old != '' && $user_password_new != '' && $user_password_check != ''){
                if(!($user_password_old == '' || password_verify($user_password_old, $member_date->user_password))){
                    $message = '舊密碼錯誤，請重新輸入';
                    return redirect()->route('member.edit', session()->get('account'))->with('error', $message);
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
                $member_date->user_password = $password_hash;
                // Member::password_update($user_account, $password_hash);
            }

            if(($user_name == '' || $user_name == $user_name_old) && $user_password_old == '' && $user_password_new == '' && $user_password_check == ''){
                $message = "請輸入修改內容";

                return redirect()->route('member.edit', session()->get('account'))->with('error', $message);
            }
            $message = "修改成功";
            $member_date->save();
            return redirect()->route('msg.index')->with('message', $message);

        }elseif(session()->get('page') == 'space'){
            $user_introduce = $request->input("user_introduce", '');
            $user_color = $request->input("user_color", '');
            $user_introduce_old = $member_date->user_introduce;
            $user_color_old = $member_date->user_color;
            // dd($user_color);

            if($user_introduce != '' && $user_introduce != $user_introduce_old){
                $request->validate([
                    'user_introduce' => 'max:500',
                ],[
                    'user_introduce.max' => '簡介需在500字之間',
                ]);
                $member_date->user_introduce = $user_introduce;
            }

            if($user_color != '' && $user_color != $user_color_old){
                $request->validate([
                    'user_color' => 'hex_color:#RRGGBB',
                ],[
                    'user_color.hex_color' => '不符合色碼標準',
                ]);
                $member_date->user_color = $user_color;
            }

            if(($user_introduce == '' || $user_introduce == $user_introduce_old) && ($user_color == '' || $user_color == $user_color_old)){
                $message = "請輸入修改內容";
                dd($member_date->user_color);
                return redirect()->route('member.show', session()->get('account'))->with('error', $message);
            }

            $message = "修改成功";
            $member_date->save();
            return redirect()->route('member.show', session()->get('account'))->with('message', $message);
        }

        

        // if(!($user_password_old == '' || password_verify($user_password_old, $sql->user_password))){
        //     $message = '舊密碼錯誤，請重新輸入';
        //     return redirect()->route('member.show', session()->get('account'))->with('error', $message);
        // }else{
        //     if($user_password_new != $user_password_check){
        //         $message = "新密碼與確認密碼不同";
        //         return redirect()->route('member.show', session()->get('account'))->with('error', $message);
        //     }else{
        //         if($user_name != ''){
        //             if(!(preg_match('/^.{2,20}$/', $user_name))){
        //                 $message = '暱稱需在2~20字間';
        //                 return redirect()->route('member.show', session()->get('account'))->with('error', $message);
        //             }else{
        //                 Member::name_update($user_account, $user_name);
        //             }
        //         }
        //         if($user_password_old != '' &&  $user_password_new != ''){
        //             if(!(preg_match('/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/', $user_password_new))){
        //                 $message = '密碼需在8~25字間，且密碼須包含英文大小寫、數字及特殊符號';
        //                 return redirect()->route('member.show', session()->get('account'))->with('error', $message);
        //             }else{
        //                 $password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);
        //                 Member::password_update($user_account, $password_hash);
        //             }
        //         }
        //         $message = "修改成功";
        //         session()->put('name', $user_name);
        
        //         return redirect()->route('msg.index')->with('message', $message);
        //     }
        // }
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
        $user_account = $request->input("user_account", '');
        $user_password = $request->input("user_password", '');
        // global $pdo;
        if($user_account == '' || $user_password == ''){
            $message = '請輸入帳號及密碼';
            return redirect()->route('member.index')->with('error', $message);
        }else{
            $sql = Member::find_member($user_account);
        }
        // dd($sql);
        // var_dump(sizeof($sql));
        // die;
        if(($sql == null)){
            $message = '帳號不存在，請重新輸入';
            return redirect()->route('member.index')->with('error', $message);
        }else{
            if(!(password_verify($user_password, $sql->user_password))){
                $message = '密碼錯誤，請重新輸入';
                return redirect()->route('member.index')->with('error', $message);
            }else{
                session()->put('account', $sql->user_account);
                session()->put('name', $sql->user_name);
                // session_start();
                // $_SESSION["user_account"] = $user_account;
                // $_SESSION["user_name"] = $user_name;
                $message = "登錄成功";

                return redirect()->route('msg.index')->with('message', $message);
            }
        }
    }
    public function logout(Request $request)
    {
        session()->flush();
        // dd(session());
        $message = "登出成功";

        return redirect()->route('msg.index')->with('message', $message);
    }
    public function space(Request $request, string $user_account) // string $account
    {

        $account = Member::find_member($user_account);
        if($account == null){
            $message = "錯誤";

            return redirect()->route('msg.index')->with('error', $message);
        }
        
        $model = array();

        $view = 'member/space';
        $img = Member::member_space($user_account);
        $model['user_spaces'] = $img;
        $user_introduce = Member::member_introduce($user_account);
        $model['account'] = $user_introduce;
        $model['img_id'] = 0;
        // dd($account);
        return View($view, $model);
    }
}
