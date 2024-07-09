<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Member extends Model
{
    protected $table = 'member';
    protected $fillable = ['user_account', 'user_name', 'user_password'];
    public $primaryKey = 'user_id';
    public $timestamps = false;

    /**
     * 搜尋帳號資訊
     */
    public static function findMember($user_account){
        $member = DB::table('member')
                    ->select('user_id', 'user_account', 'user_name', 'user_password')
                    ->where('user_account', '=', $user_account)
                    ->first();
        return $member;
    }

    /**
     * 個人空間圖片資訊
     */
    public static function memberSpaceImage($user_account){
        $img = DB::table('img_upload')
                 ->select('id', 'img_url', 'img_content', 'is_del', 'created_at')
                 ->where('user_account', '=', $user_account)
                 ->where('is_del', '=', '0')
                 ->paginate(15);
                //  ->get();
        return $img;
    }

    /**
     * 個人空間內容
     */
    public static function memberIntroduce($user_account){
        $img = DB::table('member')
                 ->select('user_id', 'user_account', 'user_name', 'user_introduce', 'user_color')
                 ->where('user_account', '=', $user_account)
                 ->first();
        return $img;
    }
    // public static function name_update($user_account, $user_name){
    //     $member = DB::table('member')
    //                 ->where('user_account', '=', $user_account)
    //                 ->update(['user_name' => $user_name]);
    //     return $member;
    // }
}
