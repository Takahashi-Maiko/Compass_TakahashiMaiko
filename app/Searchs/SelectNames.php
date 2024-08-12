<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectNames implements DisplayUsers{

  //改修課題：ユーザー検索
  // result=結果・成果
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if(empty($gender)){
      $gender = ['1', '2', '3'];
    }else{
      $gender = array($gender);
    }
    if(empty($role)){
      $role = ['1', '2', '3', '4'];
    }else{
      $role = array($role);
    }
    $users = User::with('subjects')
    ->where(function($q) use ($keyword){
      $q->where('over_name', 'like', '%'.$keyword.'%')   //WhereでLIKEを利用しあいまい検索
      ->orWhere('under_name', 'like', '%'.$keyword.'%')   //orWhereを用いて氏・名・ウジ・メイのいずれかにマッチする検索を可能にしている。
      ->orWhere('over_name_kana', 'like', '%'.$keyword.'%')
      ->orWhere('under_name_kana', 'like', '%'.$keyword.'%');
    })->whereIn('sex', $gender)
    ->whereIn('role', $role)
    ->orderBy('over_name_kana', $updown)->get();

    return $users;
  }
}
