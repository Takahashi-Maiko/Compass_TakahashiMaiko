<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category_id',
        'sub_category',
    ];

    // ↓↓MainCategory.phpとの1対多のリレーション(2024/7/21)
    public function mainCategory(){
        return $this->belongsTo('App\Models\Categories\MainCategory');
    }

    // ↓↓Post.phpとの多対多のリレーション(2024/7/21)
    public function post(){
        return $this->belongsToMany('App\Models\Posts\Post', 'post_sub_categories', 'sub_category_id', 'post_id');
    //多対多のリレーションなのでbelongsToManyをメソッドとして使用。
    //第一引数＝使用する相手のモデル
    //第二引数＝使用するテーブル名
    //第三引数＝リレーションを定義しているモデルの外部キー名
    //第四引数＝結合するモデルの外部キー名
    }
}
