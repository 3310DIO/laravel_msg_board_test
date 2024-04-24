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
    public static function findMember($user_account){
        $member = DB::table('member')
                    ->select('user_id', 'user_account', 'user_name', 'user_password')
                    ->where('user_account', '=', $user_account)
                    ->first();
        return $member;
    }
    public static function memberSpace($user_account){
        $img = DB::table('img_upload')
                 ->select('id', 'img_url', 'img_content', 'width_height', 'is_del', 'created_at')
                 ->where('user_account', '=', $user_account)
                 ->get();
        return $img;
    }
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
