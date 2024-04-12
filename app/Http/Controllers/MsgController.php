<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\MessageBoard;

class MsgController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $view = 'msg/index';
        $model = array();
        
        // $users = DB::select('select * from message_board ');
        $message_boards = MessageBoard::find_msg();
        $model['message_boards'] = $message_boards;
        $search_name['search'] = '';
        // dd($users);
        return View($view, $model, $search_name);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return View('msg/newMsg');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $msg = new MessageBoard();
        $msg->title = $request->input("title");
        $msg->user_account = $request->session()->get('account');
        $msg->content = $request->input("content");
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
        $view = 'msg/index';
        $model = array();
        $search = $request->input('search');
        $patterns = array();
        // dd($search);
        if($search == null){
            return redirect()->route('msg.index');
        }
        $patterns[0] = '/%/';
        $patterns[1] = '/_/';
        $replacements = array();
        $replacements[0] = '\%';
        $replacements[1] = '\_';
        $search_term_replace = preg_replace($patterns, $replacements, $search);
        $search_name['search'] = $search;
        // $users = DB::select('select * from message_board ');
        $message_boards = MessageBoard::search_msg($search_term_replace);
        $message_boards->appends($request->all());
        $model['message_boards'] = $message_boards;
        // dd($users);
        return View($view, $model, $search_name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $view = 'msg/edit';
        $model = array();
        
        if($id){
            $msg_edit = MessageBoard::find_edit($id);
        }
        // $users = DB::select('select * from message_board ');
        $model['msg_edit'] = $msg_edit;
        // dd($users);
        return View($view, $model);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        MessageBoard::find_update($id, $request->title, $request->content);
        $message = "修改成功";

        return redirect()->route('msg.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        MessageBoard::del_msg($id);
        $message = "刪除成功";

        return redirect()->route('msg.index')->with('message', $message);
    }
}
