<?php

namespace App\Models\Users;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Posts\Like;
use Auth;

class User extends Authenticatable
{
    use Notifiable;
    use softDeletes;

    const CREATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'over_name',
        'under_name',
        'over_name_kana',
        'under_name_kana',
        'mail_address',
        'sex',
        'birth_day',
        'role',
        'password'
    ];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // ↓↓Like.phpとの1対多のリレーション(2024/7/21)
    public function Like(){
        return $this->hasMany('App\Models\Posts\Post');
    }

    // ↓↓Post.phpとの1対多のリレーション
    public function posts(){
        return $this->hasMany('App\Models\Posts\Post');
    }

    public function calendars(){
        return $this->belongsToMany('App\Models\Calendars\Calendar', 'calendar_users', 'user_id', 'calendar_id')->withPivot('user_id', 'id');
    }

    //スクール枠登録
    public function reserveSettings(){
        return $this->belongsToMany('App\Models\Calendars\ReserveSettings', 'reserve_setting_users', 'user_id', 'reserve_setting_id')->withPivot('id');
    }

    public function subjects(){
        return $this->belongsToMany('App\Models\Users\Subjects', 'subject_users', 'user_id', 'subject_id');
    //　↑↑(2024/7/6)usersテーブルとsubjectsテーブルのリレーション(多対多)
    //多対多のリレーションなのでbelongsToManyをメソッドとして使用。
    //第一引数＝使用する相手のモデル
    //第二引数＝使用するテーブル名
    //第三引数＝リレーションを定義しているモデルの外部キー名
    //第四引数＝結合するモデルの外部キー名

    }

    // いいねしているかどうか
    public function is_Like($post_id){
        return Like::where('like_user_id', Auth::id())->where('like_post_id', $post_id)->first(['likes.id']);
    }

    // ↓↓いいねしている人のID
    public function likePostId(){
        return Like::where('like_user_id', Auth::id());
    }
}
