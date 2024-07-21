<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Like;

class Post extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_title',
        'post',
    ];

    // ↓↓User.phpとの1対多のリレーション
    public function user(){
        return $this->belongsTo('App\Models\Users\User');
    }

    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    public function subCategories(){
        // リレーションの定義
    }

    // コメント数
    public function commentCounts($post_id){
        return Post::with('postComments')->find($post_id)->postComments();
    }

    //いいね数の表示の為のLike.phpとの一対多のリレーション(2024/7/21)
    public function likes()
    {
        return $this->hasMany('App\Models\Posts\Like','like_post_id');
    }

    // ↓↓いいね数のカウント。likeCountメソッドをbladeで使用していいね数を表示させる。
    public function  likeCount()
    {
        return  $this->likes()->count();   //上で記述したlikesメッソドを使用してカウントする
    }}
