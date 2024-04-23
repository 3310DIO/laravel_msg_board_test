<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subtitle extends Model
{
    protected $table = 'msg_subtitle';
    protected $fillable = ['id', 'sub_name'];
    public $primaryKey = 'id';
    public $timestamps = false;
    // public static function find_img($id)
    // {
    //     $msg = DB::table('img_upload')
    //              ->select('user_account', 'img_url', 'img_content', 'is_del')
    //              ->where('id', '=' , $id)
    //             //  ->update(['is_del' => 1]);
    //              ->first();
    //     return $msg;
    // }
    // public static function img_update($id, $content)
    // {
    //     $msg = DB::table('img_upload')
    //              ->where('id', '=' , $id)
    //              ->update(['img_content' => $content]);
    //             //  ->get();
    //     return $msg;
    // }
    // public static function img_del($id)
    // {
    //     $msg = DB::table('img_upload')
    //              ->where('id', '=' , $id)
    //              ->update(['is_del' => 1]);
    //             //  ->get();
    //     return $msg;
    // }
}