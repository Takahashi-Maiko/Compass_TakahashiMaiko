<?php

namespace App\Models\Categories;

use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    const UPDATED_AT = null;
    const CREATED_AT = null;
    protected $fillable = [
        'main_category'
    ];

    // ↓↓SubCategory.phpとの1対多のリレーション(2024/7/21)
    public function subCategories(){
        return $this->hasMany('App\Models\Categories\SubCategory');
    }

}
