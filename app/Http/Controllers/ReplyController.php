<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use App\Models\MsgReply;
use App\Models\MessageBoard;

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
        $user_account = session()->get('account','');
        $msg_id = $request->input('msg_id');
        
        $request->validate([
            'reply' => 'required|max:1500',
            'msg_id' => 'required',
        ],[
            'reply.required' => '請輸入回覆內容',
            'reply.max' => '回覆內容不能超過1500字',
            'msg_id.required' => '請登錄',
        ]);

        if($user_account == ''){
            $message = "請登錄再回覆";

            return redirect()->route('reply.show', $request->input("msg_id"))->with('message', $message);
        }else{
            $msg_content = MessageBoard::find($msg_id);
            // dd($msg_id);
            if($msg_content->is_del){ // 判斷輸入值是否超過上限
                $message = "該文章已刪除";

                return redirect()->route('msg.index')->with('error', $message);
            }else{
                // $msg_id = $request->input("msg_id");
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
        // dd(sizeof($message_reply));
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
        ],[
            'reply.required' => '請輸入回覆內容',
            'reply.max' => '回覆內容不能超過1500字',
        ]);

        $reply = $request->input('reply');
        $user_account = session()->get('account');
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
            $reply_data->user_reply = $reply;
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
        $user_account = session()->get('account');
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
