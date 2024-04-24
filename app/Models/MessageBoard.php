<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageBoard extends Model
{
    protected $table = 'msg';
    protected $fillable = ['user_account', 'title', 'subtitle', 'content'];
    public $primaryKey = 'id';
    public $timestamps = false;
    public static function findMsg($search, $subtitle){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->join('msg_subtitle AS sub', 'm.subtitle', '=', 'sub.subtitle')
                 ->select('m.id', 'm.user_account', 'm.title', 'sub.sub_name', 'm.subtitle', 'm.content', "mem.user_color", 'm.created_at', 'm.updated_at', 'm.is_del', 'mem.user_name');
        if(isset($search) && $search != ''){
            $msg = $msg->where('m.title', 'LIKE', "%$search%");
        }
        if($subtitle != '' && $subtitle != 'all'){
            $msg = $msg->where('m.subtitle', '=', $subtitle);
        }
        $msg = $msg->orderBy('m.id', 'DESC');
        $msg = $msg->paginate(10);
                //  ->get();
        return $msg;
    }
    public static function findEdit($id){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->select('m.id', 'm.user_account', 'm.title', 'm.subtitle', 'm.content', 'm.created_at', 'm.updated_at', 'm.is_del', 'mem.user_name')
                 ->where('m.id', '=', $id)
                //  ->paginate(10);
                 ->first();
        return $msg;
    }
    // public static function del_msg($id){
    //     $msg = DB::table('msg')
    //             //  ->select('id')
    //              ->where('id', '=', $id)
    //             //  ->paginate(10);
    //              ->update(['is_del' => 1]);
    //     return $msg;
    // }
}
