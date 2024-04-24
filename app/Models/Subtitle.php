<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subtitle extends Model
{
    protected $table = 'msg_subtitle';
    protected $fillable = ['id', 'subtitle', 'sub_name'];
    public $primaryKey = 'id';
    public $timestamps = false;
    public static function get_all()
    {
        $sub = DB::table('msg_subtitle')
                 ->select('id', 'subtitle', 'sub_name')
                //  ->update(['is_del' => 1]);
                 ->get();
        return $sub;
    }
    public static function find_sub($subtitle)
    {
        $sub = DB::table('msg_subtitle')
                 ->select('id', 'subtitle', 'sub_name')
                 ->where('subtitle', '=' , $subtitle)
                 ->first();
        return $sub;
    }
    // public static function img_del($id)
    // {
    //     $msg = DB::table('img_upload')
    //              ->where('id', '=' , $id)
    //              ->update(['is_del' => 1]);
    //             //  ->get();
    //     return $msg;
    // }
}