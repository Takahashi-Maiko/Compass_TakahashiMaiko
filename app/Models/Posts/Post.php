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

    // ↓↓subCategories.phpとの多対多のリレーション(2024/7/21)
    public function subCategories(){
        return $this->belongsToMany('App\Models\Categories\Sub_Category', 'subject_users', 'post_id', 'sub_category_id');
    //多対多のリレーションなのでbelongsToManyをメソッドとして使用。
    //第一引数＝使用する相手のモデル
    //第二引数＝使用するテーブル名
    //第三引数＝リレーションを定義しているモデルの外部キー名
    //第四引数＝結合するモデルの外部キー名
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
