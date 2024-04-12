<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ImgPath extends Model
{
    use HasFactory;

    public $uploads = '/storage/upload/img/';
    protected $fillable = ['img_url'];
    public function getIamgeAttribute($img_url)
    {
        return $this->uploads . $img_url;
    }
}