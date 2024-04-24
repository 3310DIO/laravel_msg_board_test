<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    protected $table = 'img_upload';
    protected $fillable = ['user_account', 'img_url', 'width_height'];
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
}