<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use Auth;
// Validatorクラスを使用するため
use Illuminate\Support\Facades\Validator;

class PostsController extends Controller
{
    public function show(Request $request){
        $user_id = Auth::id();   //2024/7/20追加
        // dd($user_id);
        $posts = Post::with('user', 'postComments')->get();
        $categories = MainCategory::get();
        $like = new Like;
        $post_comment = new Post;
        if(!empty($request->keyword)){
            $posts = Post::with('user', 'postComments')
            ->where('post_title', 'like', '%'.$request->keyword.'%')
            ->orWhere('post', 'like', '%'.$request->keyword.'%')->get();
        }else if($request->category_word){
            $sub_category = $request->category_word;
            $posts = Post::with('user', 'postComments')->get();
        }else if($request->like_posts){
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $user_id = Auth::user()->id;   //2024/7/20追加
        // dd($user_id);
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post','user_id')); //compactの中身は44と46行目で定義した変数の$を取ったものを記述する。
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        $post = Post::create([   //Postテーブルに入れる
            'user_id' => Auth::id(),   //usersテーブルのカラムからログインしているユーザーのidを取得(ツイートしたユーザー)
            'post_title' => $request->post_title,   //投稿のタイトル
            'post' => $request->post_body   //投稿の内容
        ]);
        return redirect()->route('post.show');
    }

    // ↓↓投稿の編集(2024/7/19)
    public function postEdit(Request $request){
        $data = $request->all();
        // $validator = $request->validate([
        //     'post_title' => 'min:4|string|max:100',    //maxを50→100へ変更
        //     'post_body' => 'min:10|string|max:1000',   //maxを500→1000へ変更
        // ]);


        // ↓↓バリデーションの実施・ルールの設定
        $validation_rules = [
            'post_title' => ['required', 'string','min:4','max:100'],
            'post_body' => ['required', 'string','min:10','max:1000'],
        ];

        // ↓↓バリデーションメッセージのカスタマイズ
        $validation_message = [
                'required' => ':attributeは必須です。',
                'post_title.string' => ':attributeが不正な値です。',
                'post_body.string' => ':attributeが不正な値です。',
                'post_title.min' => ':attributeは4文字以上で入力して下さい。',
                'post_title.max' => ':attributeは100文字以内で入力して下さい。',
                'post_body.min' => ':attributeは10文字以上で入力して下さい。',
                'post_body.max' => ':attributeは1000文字以内で入力して下さい。',
            ];

            // ↓↓各リクエストの名称 >> エラーメッセージの:attributeに入る文字。
            $validation_attribute = [
                'post_title' => 'タイトル',
                'post_body' => '投稿内容',
            ];

        $validator = Validator::make($data, $validation_rules, $validation_message);


        if ($validator->fails()) {
            return redirect()->route('post.detail',['id' =>$request->post_id])
                        ->withErrors($validator)
                        ->withInput();
        }

        // バリデーション済みデータの取得
        $validated = $validator->validated();


        Post::where('id', $request->post_id)->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    // ↓↓投稿の削除(2024/7/19)
    public function postDelete($id){
        Post::findOrFail($id)->delete();
        return redirect()->route('post.show');
    }


    public function mainCategoryCreate(Request $request){
        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    public function commentCreate(Request $request){
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);
        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    // ↓↓いいねした投稿の表示
    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    // ↓↓いいねする
    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    // ↓↓いいね外す
    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
