<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MsgReply extends Model
{
    protected $table = 'msg_reply';
    protected $fillable = ['user_account', 'msg_id', 'user_reply'];
    public $primaryKey = 'id';
    public $timestamps = false;
    public static function find_content($id){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->join('msg_subtitle AS sub', 'm.subtitle', '=', 'sub.id_name')
                 ->select('m.id', 'm.user_account', 'm.title', 'm.subtitle', 'sub.sub_name', 'm.content', 'm.created_at', 'm.updated_at', 'm.is_del', 'mem.user_name')
                 ->where('m.id', $id)
                 //  ->paginate(10);
                 ->first();
        return $msg;
                 
    }
    public static function find_reply($id){
        $msg = DB::table('msg_reply AS r')
                 ->join('member AS mem', 'r.user_account', '=', 'mem.user_account')
                 ->select('r.id', 'r.msg_id', 'r.user_account', 'r.user_reply', 'r.created_at', 'r.updated_at', 'r.is_del', 'mem.user_name')
                 ->where('r.msg_id', '=', $id)
                 ->orderBy('r.created_at', 'ASC')
                //  ->paginate(10);
                ->get();
        return $msg;
                 
    }
    // public static function find_one_reply($id){
    //     $msg = DB::table('msg_reply')
    //              ->select('id', 'msg_id', 'user_account', 'user_reply', 'created_at', 'updated_at', 'is_del')
    //              ->where('id', '=', $id)
    //             //  ->paginate(10);
    //             ->first();
    //     return $msg;
                 
    // }
    // public static function find_update($id, $reply){
    //     $msg = DB::table('msg_reply')
    //             //  ->select('title', 'content')
    //              ->where('id', '=', "$id")
                 
    //             //  ->paginate(10);
    //              ->update(['user_reply' => $reply]);
    //     return $msg;
                 
    // }
    // public static function del_reply($id){
    //     $msg = DB::table('msg_reply')
    //             //  ->select('id')
    //              ->where('id', '=', "$id")
    //             //  ->paginate(10);
    //              ->update(['is_del' => 1]);
    //     return $msg;
                 
    // }
}
