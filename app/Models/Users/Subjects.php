<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Model;

use App\Models\Users\User;

class Subjects extends Model
{
    const UPDATED_AT = null;


    protected $fillable = [
        'subject'
    ];

    public function users(){
        return $this->belongsToMany('App\Models\Users\User', 'subject_users', 'subject_id', 'user_id');
    //　↑↑(2024/7/6)usersテーブルとsubjectsテーブルのリレーション(多対多)
    //多対多のリレーションなのでbelongsToManyをメソッドとして使用。
    //第一引数＝使用する相手のモデル
    //第二引数＝使用するテーブル名
    //第三引数＝リレーションを定義しているモデルの外部キー名
    //第四引数＝結合するモデルの外部キー名

    }
}
