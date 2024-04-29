<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Subtitle extends Model
{
    protected $table = 'msg_subtitle';
    protected $fillable = ['id', 'subtitle', 'sub_name'];
    public $primaryKey = 'id';
    public $timestamps = false;

    /**
     * 抓取子標題資料
     */
    public static function getAll()
    {
        $sub = DB::table('msg_subtitle')
                 ->select('id', 'subtitle', 'sub_name')
                //  ->update(['is_del' => 1]);
                 ->get();
        return $sub;
    }

    /**
     * 尋找子標題
     */
    public static function findSub($subtitle)
    {
        $sub = DB::table('msg_subtitle')
                 ->select('id', 'subtitle', 'sub_name')
                 ->where('subtitle', '=' , $subtitle)
                 ->first();
        return $sub;
    }
    // public static function img_del($id)
    // {
    //     $msg = DB::table('msg_subtitle')
    //              ->where('id', '=' , $id)
    //              ->update(['is_del' => 1]);
    //             //  ->get();
    //     return $msg;
    // }
}