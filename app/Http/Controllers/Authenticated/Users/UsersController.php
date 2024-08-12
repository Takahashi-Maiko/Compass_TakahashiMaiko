<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;


class UsersController extends Controller
{

    public function showUsers(Request $request){
        $keyword = $request->keyword;
        $category = $request->category;
        $updown = $request->updown;   //並び替え
        $gender = $request->sex;
        $role = $request->role;
        $subjects = $request->subject;// ここで検索時の科目を受け取る(2024/7/28)
        $userFactory = new SearchResultFactories();
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);
        $subjects = Subjects::all();
        // $subject_lists = Subjects::all();
        return view('authenticated.users.search', compact('users', 'subjects'));
    }

    public function userProfile($id){
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }

    public function userEdit(Request $request){
        $user = User::findOrFail($request->user_id);   //userテーブルのuser_idを取得
        $user->subjects()->sync($request->subjects);   //syncメソッドは中間テーブル(subject_users)に登録するために必要な記述
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }

    // ↓↓ユーザー検索機能(2024/8/11)
    // public function resultUsers(Request $request){

    //     $keyword = $request->input('keyword');   //'keyword'にはblade.phpのname属性を記述。(name属性で指定したキーが連想配列のキーの役割を持っている)
    //       if (isset($keyword)) {   //検索ワードが入力されていたらの処理
    //         $users = User::where('keyword', 'category', 'updown', 'gender', 'role', 'subjects','LIKE',"%$keyword%")->paginate(20);
    //         //where()でUserモデルを利用してテーブルのカラムを取得
    //     }else{   //ユーザー名が入力されていない場合の処理
    //     $users = User::get();
    //         //ユーザー名が入力されていない場合は全件表示させることにする。

    //                         //viewファイルに変数として渡す
    //     return view('user.show')->with([
    //         'keyword'=>$keyword,   //検索キーワード
    //         'category'=>$category,
    //         'updown'=>$updown,
    //         'gender'=>$gender,
    //         'role'=>$role,
    //         'subject'=>$subjects,   //選択科目
    //     ]);

    //     }
    // }
}
