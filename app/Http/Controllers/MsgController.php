<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\MessageBoard;
use App\Models\Subtitle;

class MsgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $view = 'msg/index';
        $model = array();
        $search = $request->input('search', '');
        $search_sub = $request->input('subtitle', '');
        $subtitle = Subtitle::find($search_sub);
        // dd($search_sub);
        if($search_sub != '' && $subtitle == null){
            // dd($subtitle);
            $message = "子標題錯誤";
            return redirect()->route('msg.index')->with('error', $message);
        }

        $patterns = ['/%/', '/_/'];
        // $patterns[1] = '/_/';
        // $replacements = array();
        $replacements = ['\%', '\_'];
        // $replacements[1] = '\_';
        $search_term_replace = preg_replace($patterns, $replacements, $search);
        
        // $users = DB::select('select * from message_board ');
        $message_boards = MessageBoard::find_msg($search_term_replace, $search_sub);
        $message_boards->appends($request->all());
        $model['message_boards'] = $message_boards;
        $model['search'] = $search;
        $model['subtitle'] = $search_sub;
        // dd($model);
        return View($view, $model);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return redirect()->route('msg.edit', 0);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'subtitle' => 'required|max:16',
            'title' => 'required|max:256',
            'content' => 'required|max:5000',
        ],[
            'subtitle.required' => '錯誤',
            'subtitle.max' => '錯誤',
            'title.required' => '請輸入標題',
            'title.max' => '標題不能超過256字',
            'content.required' => '請輸入內容',
            'content.max' => '內容不能超過5000字',
        ]);

        $msg = new MessageBoard();
        $subtitle = $request->input("subtitle", '');
        $title = $request->input("title", '');
        $user_account = session()->get('account');
        $content = $request->input("content", '');
        $subtitle_data = Subtitle::find($subtitle);
        if($subtitle_data == null){
            $message = "錯誤";
            return redirect()->route('msg.edit', 0)->with('error', $message);
        }
        // dd($title);

        // $validatedData = $request->validate([
        // dd($validatedData);
        // if($title == '' || $content == ''){ // 判斷是否有輸入值輸入，沒有則返回首頁

        //     $message = "非法操作";
        //     return redirect()->route('msg.index')->with('error', $message);
        // }else{
        //     if(mb_strlen($title) > 256 || mb_strlen($content) > 5000){ // 判斷輸入值是否超過上限
                
        //         $message = "輸入字元超過上限";
        //         return redirect()->route('msg.create')->with('message', $message);
        //     }else{
        $msg->subtitle = $subtitle;
        $msg->title = $title;
        $msg->user_account = $user_account;
        $msg->content = $content;
        // dd($msg);
        $msg->save();

        $message = "新增成功";
        return redirect()->route('msg.index')->with('message', $message);
        //     }
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id) // 
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $view = 'msg/newAndEditMsg';
        $model = array();
        $user_account = session()->get('account', '');
        // $msg_edit = MessageBoard::find($id);
        // dd($msg_edit);
        if(isset($id) && $id != 0){
            $msg_edit = MessageBoard::find_edit($id);
            if($msg_edit == '' || $msg_edit == null || $user_account != $msg_edit->user_account || $msg_edit->is_del){
                $message = "非法操作";
                return redirect()->route('msg.index')->with('error', $message);
            }else{
                // $users = DB::select('select * from message_board ');
                $model['msg_edit'] = $msg_edit;
                // dd($users);
            }
        }else{
            // if($user_account == ''){
            //     $message = "請登錄再留言";
            //     return redirect()->route('member.index')->with('error', $message);
            // }else{
                $msg_edit = new MessageBoard;
                $msg_edit->user_account = $user_account;
                $msg_edit->title = "";
                $msg_edit->content = "";
                $model['msg_edit'] = $msg_edit;
            // }
        }
        $model['id'] = $id;
        return View($view, $model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'subtitle' => 'required|max:16',
            'title' => 'required|max:256',
            'content' => 'required|max:5000',
        ],[
            'subtitle.required' => '錯誤',
            'subtitle.max' => '錯誤',
            'title.required' => '請輸入標題',
            'title.max' => '標題不能超過256字',
            'content.required' => '請輸入內容',
            'content.max' => '內容不能超過5000字',
        ]);
        $subtitle = $request->input('subtitle');
        $subtitle_data = Subtitle::find($subtitle);
        $user_account = session()->get('account');
        // $title = $request->input('title');
        // $content = $request->input('content');

        // if($title=='' || $content==''){ // 判斷是否有輸入值輸入，沒有則返回首頁
        //     $message = "非法操作";
        //     return redirect()->route('msg.index')->with('error', $message);
        // }else{
        //     if(mb_strlen($title) > 256 || mb_strlen($content) > 5000){ // 判斷輸入值是否超過上限
        //         $message = "輸入標題超過256個字或內容超過5000上限";
        //         return redirect()->route('msg.index')->with('error', $message);
        //     }else{
        // $msg_edit = MessageBoard::find_edit($id_msg);
        $msg_edit = MessageBoard::find($id);
        // dd($msg_edit);

        if($user_account != $msg_edit->user_account || $msg_edit == null){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            $msg_edit->subtitle = $subtitle;
            $msg_edit->title = $request->input('title');
            $msg_edit->content = $request->input('content');
            if($subtitle_data == null){
                $message = "錯誤";
                return redirect()->route('msg.edit', 0)->with('error', $message);
            }
            // $sql = "UPDATE msg SET title = ? , content = ? WHERE id = ? ";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute(["$title", "$content", "$id"]);
            // MessageBoard::find_update($id_msg, $title, $content);
            $msg_edit->save();

            $message = "修改成功";
            return redirect()->route('msg.index')->with('message', $message);
        }

        //     }
        // }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user_account = session()->get('account');
        $msg_edit = MessageBoard::find($id);
        if($user_account != $msg_edit->user_account || $msg_edit == null){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            // $sql = "UPDATE msg SET title = ? , content = ? WHERE id = ? ";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute(["$title", "$content", "$id"]);
            $msg_edit->is_del = 1;
            $msg_edit->save();

            $message = "刪除成功";

            return redirect()->route('msg.index')->with('message', $message);
        }
    }
}
