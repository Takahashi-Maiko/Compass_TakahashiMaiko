<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'like_user_id',
        'like_post_id'
    ];


    // ↓↓User.phpとの1対多のリレーション(2024/7/21)
    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }


    // ↓↓いいねの数の表示(2024/7/20)
    public function likeCounts($post_id){
        return $this->where('like_post_id', $post_id)->get()->count();
    }
}
