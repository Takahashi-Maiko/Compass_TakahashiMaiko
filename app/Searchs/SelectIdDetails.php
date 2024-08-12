<?php
namespace App\Searchs;

use App\Models\Users\User;

class SelectIdDetails implements DisplayUsers{

  // 改修課題：選択科目の検索機能
  // result=結果・成果
  // Details=詳細
  //複数選択検索の為のファイル？？
  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    if(is_null($keyword)){
      $keyword = User::get('id')->toArray();   //Userテーブルからidを取得
      // Collectionを配列(一つの変数に対して複数の要素を格納できる変数)にしようとしたときまず目に入るtoArrayというメソッドは、これを呼ぶと、Collectionが配列になった上で、更にモデルインスタンスが全てカラム名 => 値の並んだ連想配列に変換される。
      // この状態だともはや各要素はモデルオブジェクトではなくただの連想配列なので、$post->commentsのようなことはできない。
    }else{
      $keyword = array($keyword);
    }
    if(is_null($gender)){
      $gender = ['1', '2', '3'];
    }else{
      $gender = array($gender);
    }
    if(is_null($role)){
      $role = ['1', '2', '3', '4'];
    }else{
      $role = array($role);
    }
    $users = User::with('subjects')   //Userテーブルとsubjectsテーブルを取得
    ->whereIn('id', $keyword)   //whereIn=クエリビルダにて「Aカラムの値が〇〇、もしくは✕✕の情報を取得する」命令を記載する
    ->where(function($q) use ($role, $gender){
      $q->whereIn('sex', $gender)
      ->whereIn('role', $role);
    })
    ->whereHas('subjects', function($q) use ($subjects){   //whereHas=リレーション先のテーブルの条件で検索したい為使用
      $q->whereIn('subjects.id', $subjects);    //where->whereInに変更(2024/8/12)
    })
    ->orderBy('id', $updown)->get();   //orderByメソッドで記事の並び順を作成日時の昇順に設定
    return $users;
  }

}
