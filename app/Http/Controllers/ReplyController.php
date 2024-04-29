<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\View\View;
use App\Models\MsgReply;
use App\Models\MessageBoard;
use App\Http\Requests\RequestReply;

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
     * 儲存回覆
     */
    public function store(RequestReply $request)
    {
        $msg = new MsgReply();
        $msg_id = $request->input('msg_id');

        if(!(session()->has('account'))){
            $message = "請登入再回覆";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }else{
            $user_account = session()->get('account');
            $msg_content = MessageBoard::find($msg_id);
            // dd($msg_id);
            if($msg_content->is_del){
                $message = "該文章已刪除";

                return redirect()->route('msg.index')->with('error', $message);
            }else{
                $user_reply = $request->input("reply");

                $msg->user_account = $user_account;
                $msg->msg_id = $msg_id;
                $msg->user_reply = $user_reply;
                // dd($msg);
                $msg->save();
                $message = "回覆成功";

                return redirect()->route('reply.show', $msg_id)->with('message', $message);
            }
        }
    }

    /**
     * 顯示回覆
     */
    public function show(Request $request, string $id)
    {
        $view = 'reply/reply';
        $model = array();
        // $users = DB::select('select * from message_board ');
        $message_content = MsgReply::findContent($id);
        // dd($message_content);
        if($message_content == null){
            $message = "留言不存在";

            return redirect()->route('msg.index')->with('error', $message);
        }
        $model['message_content'] = $message_content;
        $message_reply = MsgReply::findReply($id);
        // dd(sizeof($message_reply));
        $model['message_reply'] = $message_reply;
        return view($view, $model);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * 更新回覆
     */
    public function update(RequestReply $request, string $id)
    {
        $reply = $request->input('reply');
        $user_account = session()->get('account');
        $reply_data = MsgReply::find($id);
        // dd($reply_data);
        if($user_account != $reply_data->user_account){
            $message = "非法操作";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('error', $message);
        }else{
            $reply_data->user_reply = $reply;
            $reply_data->save();
            $message = "修改成功";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }
    }

    /**
     * 移除回覆
     */
    public function destroy(Request $request, string $id)
    {
        $user_account = session()->get('account');
        $reply_data = MsgReply::find($id);
        if($reply_data == null || $user_account != $reply_data->user_account){
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            $reply_data->is_del = 1;
            $reply_data->save();
            $message = "刪除成功";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }
    }
}
