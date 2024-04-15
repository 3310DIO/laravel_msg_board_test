<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MessageBoard extends Model
{
    protected $table = 'msg';
    protected $fillable = ['user_account', 'title', 'content'];
    public $primaryKey = 'id';
    public $timestamps = false;
    public static function find_msg(){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->select('m.id', 'm.user_account', 'm.title', 'm.content', 'm.created_at', 'm.update_at', 'm.is_del', 'mem.user_name')
                 ->orderBy('m.id', 'DESC')
                 ->paginate(10);
                //  ->get();
        return $msg;
    }
    public static function search_msg($search){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->select('m.id', 'm.user_account', 'm.title', 'm.content', 'm.created_at', 'm.update_at', 'm.is_del', 'mem.user_name')
                 ->where('m.title', 'like', "%$search%")
                 ->orderBy('m.update_at', 'DESC')
                 ->paginate(10);
                //  ->get();
        return $msg;
    }
    public static function find_edit($id){
        $msg = DB::table('msg AS m')
                 ->join('member AS mem', 'm.user_account', '=', 'mem.user_account')
                 ->select('m.id', 'm.user_account', 'm.title', 'm.content', 'm.created_at', 'm.update_at', 'm.is_del', 'mem.user_name')
                 ->where('m.id', '=', "$id")
                //  ->paginate(10);
                 ->first();
        return $msg;
    }
    public static function find_update($id, $title, $content){
        $msg = DB::table('msg')
                //  ->select('title', 'content')
                 ->where('id', '=', "$id")
                 
                //  ->paginate(10);
                 ->update(['title' => $title, 'content' => $content]);
        return $msg;
    }
    public static function del_msg($id){
        $msg = DB::table('msg')
                //  ->select('id')
                 ->where('id', '=', "$id")
                //  ->paginate(10);
                 ->update(['is_del' => 1]);
        return $msg;
    }
}
