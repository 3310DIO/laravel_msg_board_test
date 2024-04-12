<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\MsgReply;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $msg = new MsgReply();
        $user_account = $request->session()->get('account','');
        if($user_account == ''){
            $message = "請登錄再回復";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }
        $msg->user_account = $user_account;
        $msg->msg_id = $request->input("msg_id");
        $msg->user_reply = $request->input("reply");
        // dd($msg);
        $msg->save();
        $message = "回覆成功";

        return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $view = 'reply/reply';
        $model_content = array();
        $model_reply = array();
        $floor_id['floor_id'] = 0;
        
        // $users = DB::select('select * from message_board ');
        $message_content = MsgReply::find_content($id);
        $model_content['message_content'] = $message_content;
        $message_reply = MsgReply::find_reply($id);
        $model_reply['message_reply'] = $message_reply;
        // dd($model_content);
        // dd($model_reply);
        // dd($id);
        // dd($users);
        return View($view, $model_content, $model_reply, $floor_id);
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
    public function update(Request $request, string $id)
    {
        MsgReply::find_update($id, $request->reply);
        $message = "修改成功";

        return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        MsgReply::del_reply($id);
        $message = "刪除成功";

        return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
    }
}
