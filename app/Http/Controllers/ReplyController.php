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
        
        $request->validate([
            'reply' => 'required|max:1500',
            'msg_id' => 'required',
        ]);

        if($user_account == ''){
            $message = "請登錄再回復";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }else{
            // if(mb_strlen($user_reply) > 1500){ // 判斷輸入值是否超過上限
            //     $message = "輸入字元超過1500上限";

            //     return redirect()->route('reply.show', $request->input("msg_id"))->with('error', $message);
            // }else{

            $msg_id = $request->input("msg_id");
            $user_reply = $request->input("reply");

            $msg->user_account = $user_account;
            $msg->msg_id = $msg_id;
            $msg->user_reply = $user_reply;
            // dd($msg);
            $msg->save();
            $message = "回覆成功";

            return redirect()->route('reply.show', $msg_id)->with('message', $message);
            // }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $view = 'reply/reply';
        $model = array();
        
        // $users = DB::select('select * from message_board ');
        $message_content = MsgReply::find_content($id);
        if($message_content == null){
            $message = "留言不存在";

            return redirect()->route('msg.index')->with('error', $message);
        }
        $model['message_content'] = $message_content;
        $message_reply = MsgReply::find_reply($id);
        $model['message_reply'] = $message_reply;
        // dd($model_reply);
        // dd($model_content);
        // dd($model_reply);
        // dd($id);
        // dd($users);
        return View($view, $model);
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
        $request->validate([
            'reply' => 'required|max:1500',
        ]);

        $reply = $request->input('reply');
        $user_account = $request->session()->get('account');
        // if(mb_strlen($reply) > 1500){ // 判斷輸入值是否超過上限
        //     $message = "輸入字元超過上限";

        //     return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        // }else{
        $reply_data = MsgReply::find($id);
        // dd($reply_data);
        if($user_account != $reply_data->user_account){ // 判斷是否為發布者進行的修改
            $message = "非法操作";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('error', $message);
        }else{
            $reply_data->reply = $reply;
            $reply_data->save();
            // MsgReply::find_update($msg_id, $reply);
            $message = "修改成功";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }
        // }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $user_account = $request->session()->get('account');
        $reply_data = MsgReply::find($id);
        if($user_account != $reply_data->user_account || $reply_data == null){ // 判斷是否是發佈者進行的修改
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            // $sql = "UPDATE msg SET title = ? , content = ? WHERE id = ? ";
            // $stmt = $pdo->prepare($sql);
            // $stmt->execute(["$title", "$content", "$id"]);
            $reply_data->is_del = 1;
            $reply_data->save();
            // MsgReply::del_reply($id);
            $message = "刪除成功";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }
    }
}
