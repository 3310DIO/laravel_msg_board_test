<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\View\View;
use App\Models\MessageBoard;
use App\Models\Subtitle;
use App\Http\Requests\RequestMsg;

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
        $subtitle = Subtitle::findSub($search_sub);
        // dd($search_sub);
        if($search_sub != '' && $subtitle == null){
            // dd($subtitle);
            $message = "子標題錯誤";
            return redirect()->route('msg.index')->with('error', $message);
        }

        $patterns = ['/%/', '/_/'];
        $replacements = ['\%', '\_'];
        $search_term_replace = preg_replace($patterns, $replacements, $search);
        
        // $users = DB::select('select * from message_board ');
        $message_boards = MessageBoard::findMsg($search_term_replace, $search_sub);
        $message_boards->appends($request->all());
        $model['message_boards'] = $message_boards;
        $model['subtitle_bars'] = Subtitle::getAll();
        $model['search'] = $search;
        $model['subtitle'] = $search_sub;
        // dd($message_boards);
        return view($view, $model);
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
    public function store(RequestMsg $request)
    {
        $msg = new MessageBoard();
        $subtitle = $request->input("subtitle", '');
        $title = $request->input("title", '');
        $user_account = session()->get('account');
        $content = $request->input("content", '');
        $subtitle_data = Subtitle::findSub($subtitle);
        if($subtitle_data == null){
            $message = "錯誤";
            return redirect()->route('msg.edit', 0)->with('error', $message);
        }
        // dd($title);

        $msg->subtitle = $subtitle;
        $msg->title = $title;
        $msg->user_account = $user_account;
        $msg->content = $content;
        // dd($msg);
        $msg->save();

        $message = "新增成功";
        return redirect()->route('msg.index')->with('message', $message);
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
        // dd($msg_edit);
        if(isset($id) && $id != 0){
            $msg_edit = MessageBoard::findEdit($id);
            if($msg_edit == null || $msg_edit == '' || $user_account != $msg_edit->user_account || $msg_edit->is_del){
                $message = "非法操作";
                return redirect()->route('msg.index')->with('error', $message);
            }else{
                $model['msg_edit'] = $msg_edit;
                // dd($users);
            }
        }else{
                $msg_edit = new MessageBoard;
                $msg_edit->user_account = $user_account;
                $msg_edit->title = "";
                $msg_edit->content = "";
                $model['msg_edit'] = $msg_edit;
        }
        $model['subtitle_bars'] = Subtitle::getAll()->slice(1);
        // dd($model);
        $model['id'] = $id;
        return view($view, $model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RequestMsg $request, string $id)
    {
        $subtitle = $request->input('subtitle');
        $subtitle_data = Subtitle::findSub($subtitle);
        $user_account = session()->get('account');
        $msg_edit = MessageBoard::find($id);
        // dd($msg_edit);

        if($msg_edit == null || $user_account != $msg_edit->user_account){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            $msg_edit->subtitle = $subtitle;
            $msg_edit->title = $request->input('title');
            $msg_edit->content = $request->input('content');
            // dd($subtitle_data);
            if($subtitle_data == null){
                $message = "錯誤";
                return redirect()->route('msg.edit', 0)->with('error', $message);
            }
            $msg_edit->save();

            $message = "修改成功";
            return redirect()->route('msg.index')->with('message', $message);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user_account = session()->get('account');
        $msg_edit = MessageBoard::find($id);
        if($msg_edit == null || $user_account != $msg_edit->user_account){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            $msg_edit->is_del = 1;
            $msg_edit->save();

            $message = "刪除成功";

            return redirect()->route('msg.index')->with('message', $message);
        }
    }
}
