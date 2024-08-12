<?php
namespace App\Searchs;

use App\Models\Users\User;

class AllUsers implements DisplayUsers{

  // ↓↓キーワード・カテゴリー・性別・権限・教科　全てのユーザーの取得??
  // result=結果・成果
  // implementsでDisplayUsersクラスとALLUsersクラスをインターフェースで実装する
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $users = User::all();   //Userテーブルからユーザー情報を取得してユーザー検索画面へ表示
    // dd($users);
    return $users;
  }


}
