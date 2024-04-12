<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        dd($request->all());
        $user_account = $request->session()->get('account');
        $img_name = $_FILES['my_img']['name'];
        $img_size = $_FILES['my_img']['size'];
        $img_tmp_name = $_FILES['my_img']['tmp_name'];
        $img_error = $_FILES['my_img']['error'];
        $img_w_h = getimagesize($_FILES['my_img']['tmp_name']);
        $width = $img_w_h[0]; // 獲得寬度
        $height = $img_w_h[1]; // 獲得高度
        if($width > $height){ // 判斷高還是寬比較大
            $w_h = 1;
        }else{
            $w_h = 0;
        }

        if(!($img_error === 0)){ // 判斷是否有錯誤訊息
            echo "<script>";
            echo "alert('未知的錯誤');";
            echo "window.history.go(-1);";
            echo "</script>";
        }else{
            if($img_size > 10485760){ // 判斷大小是否超過10MB
                echo "<script>";
                echo "alert('上傳影像不能超過10MB');";
                echo "window.history.go(-1);";
                echo "</script>";
            }else{
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                // echo"$img_ex";
                $img_ex_lc = strtolower($img_ex);

                $allow_img = array("jpg", "jpeg", "png"); // 允許的圖片格式
                if(!(in_array($img_ex_lc, $allow_img))){ // 判斷格式是否符合預設條件
                    echo "<script>";
                    echo "alert('不支援的影像格式');";
                    echo "window.history.go(-1);";
                    echo "</script>";
                }else{
                    $new_img_name = uniqid("IMG-", true).'.'.$img_ex_lc;
                    $img_upload_path = 'upload/img/'.$new_img_name;
                    move_uploaded_file($img_tmp_name, $img_upload_path); // 將上傳檔案複製進指定資料夾

                    //$sql = "INSERT INTO img_upload(user_account, img_url, width_height) VALUES (?, ?, ?)"; // VALUES (?,?,?) 使用?代表輸入值。
                    //$stmt = $pdo->prepare($sql);
                    //$stmt->execute([$user_account, $new_img_name, $w_h]); // array($msg_name, $title, $content) 將獲取的值輸入進?中。
                    echo "<script>";
                    echo "alert('上傳成功');";
                    echo "location.href='user_space.php?owner=$user_account';";
                    echo "</script>";
                }
            }
        }
        $request->file('my_img')->store('public/storge/upload/img');
        return '上傳完成。';
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
