<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return View('member/login');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View('member/register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $member = new Member();
        $user_account = $request->input("user_account");
        $user_name = $request->input("user_name");
        $user_password = $request->input("user_password");
        $user_password_check = $request->input("user_password_check");
        // dd($msg);
        if($user_password != $user_password_check){
            $message = '密碼及確認密碼需相同';
            return redirect()->route('member.create')->with('error', $message);
        }else{
            if($user_account == '' || $user_name == '' || $user_password == ''){
                $message = '禁止輸入空字元';
                return redirect()->route('member.create')->with('error', $message);
            }else{
                if(!(preg_match('/^[a-zA-Z0-9]{8,20}$/', $user_account) && preg_match('/^.{2,20}$/', $user_name))){
                    $message = '帳號需在8~20字間且為英文或數字，暱稱需在2~20字間';
                    return redirect()->route('member.create')->with('error', $message);
                }else{
                    if(!(preg_match('/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/', $user_password))){
                        $message = '密碼需在8~25字間，且密碼須包含英文大小寫、數字及特殊符號';
                        return redirect()->route('member.create')->with('error', $message);
                    }else{
                        $sql = Member::find_member($user_account);
                        if($sql[0]->user_account != ''){
                            $message = '該帳號已存在';
                            return redirect()->route('member.index')->with('message', $message);
                        }else{ // 將輸入的密碼加密後與帳號跟暱稱從入資料庫
                            $password_hash = password_hash($user_password, PASSWORD_DEFAULT);
                            $member->user_account = $user_account;
                            $member->user_name = $user_name;
                            $member->user_password = $password_hash;
                            $member->save();
                            $request->session()->put('account', $user_account);
                            $request->session()->put('name', $user_name);
                            // session_start();
                            // $_SESSION["user_account"] = $user_account;
                            // $_SESSION["user_name"] = $user_name;
                            $message = "登錄成功";

                            return redirect()->route('msg.index')->with('message', $message);
                        }
                    }
                }
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return View('member/edit_user');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $account)
    {
        $user_account = $account;
        // dd($user_account);
        $user_name = $request->input("user_name", '');
        $user_password_old = $request->input("user_password_old", '');
        $user_password_new = $request->input("user_password_new", '');
        $user_password_check = $request->input("user_password_check", '');
        $sql = Member::find_member($user_account);
        if(!($user_password_old == '' || password_verify($user_password_old, $sql[0]->user_password))){
            $message = '舊密碼錯誤，請重新輸入';
            return redirect()->route('member.show', $request->session()->get('account'))->with('error', $message);
        }else{
            if($user_password_new != $user_password_check){
                $message = "新密碼與確認密碼不同";
                return redirect()->route('member.show', $request->session()->get('account'))->with('error', $message);
            }else{
                if($user_name != ''){
                    if(!(preg_match('/^.{2,20}$/', $user_name))){
                        $message = '暱稱需在2~20字間';
                        return redirect()->route('member.show', $request->session()->get('account'))->with('error', $message);
                    }else{
                        Member::name_update($user_account, $user_name);
                    }
                }
                if($user_password_old != '' &&  $user_password_new != ''){
                    if(!(preg_match('/^(?=.*\d)(?=.*[a-zA-Z])(?=.*\W).{8,25}$/', $user_password_new))){
                        $message = '密碼需在8~25字間，且密碼須包含英文大小寫、數字及特殊符號';
                        return redirect()->route('member.show', $request->session()->get('account'))->with('error', $message);
                    }else{
                        $password_hash = password_hash($user_password_new, PASSWORD_DEFAULT);
                        Member::password_update($user_account, $password_hash);
                    }
                }
                $message = "修改成功";
                $request->session()->put('name', $user_name);
        
                return redirect()->route('msg.index')->with('message', $message);
            }
        }
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
        if((sizeof($sql) == 0)){
            $message = '帳號不存在，請重新輸入';
            return redirect()->route('member.index')->with('error', $message);
        }else{
            if(!(password_verify($user_password, $sql[0]->user_password))){
                $message = '密碼錯誤，請重新輸入';
                return redirect()->route('member.index')->with('error', $message);
            }else{
                $request->session()->put('account', $sql[0]->user_account);
                $request->session()->put('name', $sql[0]->user_name);
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
        $request->session()->flush();
        // dd($request->session());
        $message = "登出成功";

        return redirect()->route('msg.index')->with('message', $message);
    }
    public function space(string $user_account) // string $account
    {
        $account = $user_account;
        $model = array();
        $user_account = array();

        $view = 'member/space';
        $sql = Member::member_space($account);
        $model['user_spaces'] = $sql;
        $user_account['account'] = $account;
        $img_id['img_id'] = 0;
        // dd($account);
        return View($view, $model, $user_account, $img_id);
    }
}
