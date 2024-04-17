<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;

class ImageController extends Controller
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
        $img_data = $request->file('my_img');
        $user_account = $request->session()->get('account');
        $img_name = $img_data->getClientOriginalName();
        $img_size = $img_data->getSize();
        $img_tmp_name = $img_data->getPathname();
        $img_error = $img_data->getError();
        $img_w_h = getimagesize($img_tmp_name);
        // dd($request->my_img);
        // dd($request->getSchemeAndHttpHost() . '/storage/upload/img/');
        $width = $img_w_h[0]; // 獲得寬度
        $height = $img_w_h[1]; // 獲得高度
        if($width > $height){ // 判斷高還是寬比較大
            $w_h = 1;
        }else{
            $w_h = 0;
        }
        if(!($request->hasFile('my_img'))){
            $message = '非法操作';
            return redirect()->route('member.space', $user_account)->with('error', $message);
        }else{
            if(!($img_error === 0)){ // 判斷是否有錯誤訊息
                $message = '未知的錯誤';
                return redirect()->route('member.space', $user_account)->with('error', $message);
            }else{
                if($img_size > 10485760){ // 判斷大小是否超過10MB
                    $message = '上傳影像不能超過10MB';
                    return redirect()->route('member.space', $user_account)->with('error', $message);
                }else{
                    $img_ex = pathinfo($img_name, PATHINFO_EXTENSION); // 只保留副檔名
                    // echo"$img_ex";
                    $img_ex_lc = strtolower($img_ex); // 轉成小寫

                    $allow_img = array("jpg", "jpeg", "png"); // 允許的圖片格式
                    if(!(in_array($img_ex_lc, $allow_img))){ // 判斷格式是否符合預設條件
                        $message = '不支援的影像格式';
                        return redirect()->route('member.space', $user_account)->with('error', $message);
                    }else{
                        $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc; // 產生一個隨機名稱加上原本的副檔名
                        $img_upload_path = $request->getSchemeAndHttpHost() . '/upload/img/'.$new_img_name; 
                        $img_data->move(public_path('/storage/upload/img/'), $img_upload_path); // 將上傳檔案複製進指定資料夾

                        //$sql = "INSERT INTO img_upload(user_account, img_url, width_height) VALUES (?, ?, ?)"; // VALUES (?,?,?) 使用?代表輸入值。
                        //$stmt = $pdo->prepare($sql);
                        //$stmt->execute([$user_account, $new_img_name, $w_h]); // array($msg_name, $title, $content) 將獲取的值輸入進?中。
                        $new_img = new Image;
                        $new_img->user_account = $user_account;
                        $new_img->img_url = $new_img_name;
                        $new_img->width_height = $w_h;
                        // dd($msg);
                        $new_img->save();
                        $message = '上傳成功';
                        return redirect()->route('member.space', $user_account)->with('message', $message);
                    }
                }
            }
        }
        
        // $request->file('my_img')->store('public/storge/upload/img');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
            'img_content' => 'required|max:200',
        ]);
        $id_img = $request->input('img_id');
        $user_account = $request->session()->get('account');
        $content = $request->input('img_content');
        if($content==''){ // 判斷是否有輸入值輸入，沒有則返回首頁
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            // if(mb_strlen($content) > 200){ // 判斷輸入值是否超過上限
            //     $message = "輸入超過200個字";
            //     return redirect()->route('msg.index')->with('error', $message);
            // }else{
            $img_data = Image::find($id_img);
            // dd($id_img);
            if($user_account != $img_data->user_account || $img_data == null){ // 判斷是否是發佈者進行的修改
                $message = "非法操作";
                return redirect()->route('msg.index')->with('error', $message);
            }else{
                // $sql = "UPDATE msg SET title = ? , content = ? WHERE id = ? ";
                // $stmt = $pdo->prepare($sql);
                // $stmt->execute(["$title", "$content", "$id"]);
                $img_data->img_content = $content;
                $img_data->save();
                // Image::img_update($id_img, $content);
                $message = "修改成功";
                return redirect()->route('member.space', $user_account)->with('message', $message);
            }
            // }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        // dd("測試");
        $user_account = $request->session()->get('account');
        $img_data = Image::find($id);
        if($user_account != $img_data->user_account || $img_data == null){ // 判斷是否是發佈者進行的修改
            $message = "非法操作";
            return redirect()->route('msg.index')->with('error', $message);
        }else{
            $new_img_name = $img_data->img_url;
            $img_upload_path = 'public/upload/img/'.$new_img_name;
            $img_del_path = 'public/upload/del_img/'.$new_img_name;
            // dd(URL($img_upload_path),URL($img_del_path));
            Storage::move($img_upload_path, $img_del_path); // 將上傳檔案複製進指定資料夾
            $img_data->is_del = 1;
            $img_data->save();
            // Image::img_del($id);
            $message = "刪除成功";

            return redirect()->route('member.space', $user_account)->with('message', $message);
        }
    }
}
