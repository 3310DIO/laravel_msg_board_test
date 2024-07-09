<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use Illuminate\View\View;
use App\Models\MessageBoard;
use App\Models\Subtitle;
use App\Http\Requests\RequestMsg;
use Illuminate\Validation\Rule;

class MsgController extends Controller
{
    /**
     * 留言板首頁
     */
    public function index(Request $request)
    {
        // $subtitle = Subtitle::pluck('subtitle')->toArray();
        // dd(array_values($subtitle));
        $view = 'msg/index';
        $model = array();
        $subtitle = Subtitle::pluck('subtitle')->toArray();
        $request->validate([
            'subtitle' => [Rule::in($subtitle)],
        ],[
            'subtitle.in' => '子標題錯誤',
        ]);

        $search = $request->input('search', '');
        $search_sub = $request->input('subtitle', '');
        $patterns = ['/%/', '/_/'];
        $replacements = ['\%', '\_'];
        $search_term_replace = preg_replace($patterns, $replacements, $search);
        
        // $users = DB::select('select * from message_board ');
        $message_boards = MessageBoard::findMsg($search_term_replace, $search_sub);
        $message_boards->appends($request->all());
        $model['message_boards'] = $message_boards;
        $model['subtitle_bars'] = Subtitle::all();
        $model['search'] = $search;
        $model['subtitle'] = $search_sub;
        // dd($model);
        return view($view, $model);
    }

    /**
     * 新增留言板
     */
    public function create()
    {
        return redirect()->route('msg.edit', 0);
    }

    /**
     * 儲存留言內容
     */
    public function store(RequestMsg $request)
    {
        $msg = new MessageBoard();
        $subtitle = $request->input("subtitle", '');
        $title = $request->input("title", '');
        $user_account = session()->get('account');
        $content = $request->input("content", '');
        // $content = htmlspecialchars($content);
        // $content = $this->convertUrlsToLinks($content);

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
     * 修改留言內容
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
     * 更新留言內容
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
            $content = $request->input('content');
            // $content = htmlspecialchars($content);
            // $content = $this->convertUrlsToLinks($content);
            $msg_edit->content = $content;
            // dd($subtitle_data);
            if($subtitle_data == null){
                $message = "錯誤";
                return redirect()->route('msg.edit', 0)->withInput()->with('error', $message);
            }
            $msg_edit->save();

            $message = "修改成功";
            return redirect()->route('msg.index')->with('message', $message);
        }
    }

    /**
     * 移除留言
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
    // private function convertUrlsToLinks($message)
    // {
    //     $pattern = '/(http:\/\/|https:\/\/|www\.)[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|\/))/';
    //     return preg_replace($pattern, '<a href="$0" target="_blank">$0</a>', $message);
    // }
}
