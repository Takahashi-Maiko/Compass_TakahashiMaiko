<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Models\Posts\PostComment;

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

    public function subCategories(){
        // リレーションの定義
    }

    // ↓↓postComments.phpとの1対多のリレーション(2024/7/21)
    public function postComments(){
        return $this->hasMany('App\Models\Posts\PostComment');
    }

    // コメント数
    public function commentCount($post_id){
        // return Post::with('postComments')->find($post_id)->postComments();
         return  $this->postComments()->count();   //上で記述したpostCommentsメッソドを使用してカウントする
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
