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
        $updown = $request->updown;
        $gender = $request->sex;
        $role = $request->role;
        $subjects = $request->subject;// ここで検索時の科目を受け取る(2024/7/28)
        $userFactory = new SearchResultFactories();
        $users = $userFactory->initializeUsers($keyword, $category, $updown, $gender, $role, $subjects);
        $subjects = Subjects::all();
        $subject_lists = Subjects::all();
        return view('authenticated.users.search', compact('users', 'subjects', 'subject_lists'));
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
}
