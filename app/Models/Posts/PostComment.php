<?php

namespace App\Models\Posts;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class PostComment extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;

    protected $fillable = [
        'post_id',
        'user_id',
        'comment',
    ];

    // ↓↓Post.phpとの1対多のリレーション(2024/7/21)
    public function post(){
        return $this->belongsTo('App\Models\Posts\Post');
    }

    // ↓↓User.phpとの1対多のリレーション(2024/7/21)
    public function user()
    {
        return $this->belongsTo('App\Models\Users\User');
    }

    public function commentUser($user_id){
        return User::where('id', $user_id)->first();
    }
}
