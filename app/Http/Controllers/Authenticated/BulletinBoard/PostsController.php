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
    // ↓↓投稿表示/検索機能 サブカテゴリーでの検索機能実装する
    public function show(Request $request){
        $user_id = Auth::id();   //2024/7/20追加
        // dd($user_id);
        $posts = Post::with('user', 'postComments')->get();   //postテーブルからユーザーとコメント取得
        $categories = MainCategory::with('subCategory')->get();   //メインカテゴリーテーブルととサブカテゴリーテーブルの取得
        $like = new Like;   //いいね
        $post_comment = new Post;   //コメント
        if(!empty($request->keyword)){   //キーワードの取得
            $posts = Post::with('user', 'postComments')   //postテーブルとusersテーブルとpost commentsテーブルの取得。クラスを指定したのちwhere句の記載
            ->where('post_title', 'like', '%'.$request->keyword.'%')
             //第一引数=該当するカラム名 第二引数=あいまい検索の為のlike 第三引数=どの一致七日の条件(今回はキーワード)   前後に％をつけ部分一致判定にする。
            ->orWhere('post', 'like', '%'.$request->keyword.'%')
             //第一引数=該当するカラム名 第二引数=あいまい検索の為のlike 第三引数=どの一致七日の条件(今回はキーワード)   前後に％をつけ部分一致判定にする。
            // ↓↓サブカテゴリー検索機能(完全一致)追加 2024/8/17
            ->orwhereHas('subCategory', function($q) use ($request){   //'sub_category'はpostとのリレーションのメソッド名を使用
            $q->where('sub_category', '=', $request->keyword);   //完全一致なのでwhereを使用
            })->get();
        }else if($request->category_word){   //カテゴリー
            $sub_category = $request->category_word;   //サブカテゴリーでの検索
            // $sub_category = SubCategory::where('sub_category', '=', '$request->category_word')->get();
            $posts = Post::with('user', 'postComments')->get();
        }else if($request->like_posts){   //いいねした投稿 like_postsはinputタグの中のname属性
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $posts = Post::with('user', 'postComments')
            ->whereIn('id', $likes)->get();
        }else if($request->my_posts){   //自分の投稿 my_postsはinputタグの中のname属性
            $posts = Post::with('user', 'postComments')
            ->where('user_id', Auth::id())->get();
        // ↓↓サブカテゴリーに紐づいた投稿を表示させる 2024/8/18
        }else if($request->sub_category_posts){   //サブカテゴリーに紐づいた投稿 sub_category_postsはinputタグの中のname属性
            $posts = Post::with('user', 'postComments')
            ->orwhereHas('subCategory', function($q) use ($request){   //'sub_category'はpostとのリレーションのメソッド名を使用
            $q->where('sub_category', '=', $request->sub_category_posts);   //完全一致なのでwhereを使用
            })->get();
        }
        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $user_id = Auth::user()->id;   //2024/7/20追加
        // dd($user_id);
        $post = Post::with('user', 'postComments')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post','user_id')); //compactの中身は49と51行目で定義した変数の$を取ったものを記述する。
    }

    public function postInput(){
        $main_categories = MainCategory::get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request){
        // dd($request);
        $post = Post::create([   //Postテーブルに入れる
            'user_id' => Auth::id(),   //usersテーブルのカラムからログインしているユーザーのidを取得(ツイートしたユーザー)
            'post_title' => $request->post_title,   //投稿のタイトル
            'post' => $request->post_body   //投稿の内容
        ]);

        $post->SubCategory()->attach($request->sub_category_id);   //attachメソッドは中間テーブル(post_sub_categories)に登録するために必要な記述

        return redirect()->route('post.show');
    }

    // ↓↓投稿の編集(2024/7/19)
    public function postEdit(Request $request){
        $data = $request->all();

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

    // ↓↓メインカテゴリー追加機能・バリデーションつける
    public function mainCategoryCreate(Request $request){
        $data = $request->all();

        // ・必須項目
        // ・100文字以内
        // ・文字列型
        // ・同じ名前のメインカテゴリーは登録できない
        // ↓↓バリデーションの実施・ルールの設定
        $validation_rules = [
            'main_category_name' => ['required', 'string','max:100','unique:main_categories,main_category_name'],
        ];

        // ↓↓バリデーションメッセージのカスタマイズ
        $validation_message = [
                'required' => ':attributeは必須です。',
                'main_category_name.string' => ':attributeが不正な値です。',
                'main_category_name.max' => ':attributeは100文字以内で入力して下さい。',
                'main_category_name.unique' => '登録済みのメインカテゴリーは追加出来ません。',
            ];

            // ↓↓各リクエストの名称 >> エラーメッセージの:attributeに入る文字。
            $validation_attribute = [
                'main_category_name' => 'メインカテゴリー',
            ];

        $validator = Validator::make($data, $validation_rules, $validation_message);


        if ($validator->fails()) {
            return redirect()->route('post.input')
                        ->withErrors($validator)
                        ->withInput();
        }

        // バリデーション済みデータの取得
        $validated = $validator->validated();


        MainCategory::create(['main_category' => $request->main_category_name]);
        return redirect()->route('post.input');
    }

    // ↓↓サブカテゴリー追加機能・バリデーションつける(2024/7/25)
    public function subCategoryCreate(Request $request){
        $data = $request->all();

        $validation_rules = [
            'main_category_id' => ['required','exists:main_categories,id'],
             //exists=データベース内のname属性と同名のカラム内に入力値が存在するかをチェックする。name属性自体をカラム名にしないといけないので注意。
            'sub_category_name' => ['required', 'string','max:100','unique:sub_categories,sub_category'],
        ];

        // ↓↓バリデーションメッセージのカスタマイズ
        $validation_message = [
                'required' => ':attributeは必須です。',
                'sub_category_name.string' => ':attributeが不正な値です。',
                'sub_category_name.max' => ':attributeは100文字以内で入力して下さい。',
                'sub_category_name.unique' => '登録済みのサブカテゴリーは追加出来ません。',
            ];

            // ↓↓各リクエストの名称 >> エラーメッセージの:attributeに入る文字。
            $validation_attribute = [
                'sub_category_name' => 'サブカテゴリー',
                'main_category_id' => 'メインカテゴリー',
            ];

        $validator = Validator::make($data, $validation_rules, $validation_message);


        if ($validator->fails()) {
            return redirect()->route('post.input')
                        ->withErrors($validator)
                        ->withInput();
        }

        // バリデーション済みデータの取得
        $validated = $validator->validated();


        SubCategory::create([
            'sub_category' => $request->sub_category_name,
            'main_category_id' => $request->main_category_id,
        ]);
        // メインカテゴリーカラムも追加する

        // $main_category_id = MainCategory::find(id);
        return redirect()->route('post.input' ,['id' => $request->main_category_id,]);
    }

    // ↓↓コメント機能・バリデーション作成(2024/7/21)
    public function commentCreate(Request $request){

        // $validator = $request->validate([   //バリデーションの設定：入力必須・1～150文字以内
        //     'comment' => ['required','string','max:250'],   //入力必須・文字であること・150文字以内   newPostはname属性
        // ]);

        $data = $request->all();

        // ↓↓バリデーションの実施・ルールの設定
        $validation_rules = [
            'comment' => ['required', 'string','max:250'],
        ];

        // ↓↓バリデーションメッセージのカスタマイズ
        $validation_message = [
                'required' => ':attributeは必須です。',
                'comment.string' => ':attributeが不正な値です。',
                'comment.max' => ':attributeは250文字以内で入力して下さい。',
            ];

            // ↓↓各リクエストの名称 >> エラーメッセージの:attributeに入る文字。
            $validation_attribute = [
                'comment' => 'コメント',
            ];

        $validator = Validator::make($data, $validation_rules, $validation_message);

        if ($validator->fails()) {
            return redirect()->route('post.input',['id' =>$request->post_id])
                        ->withErrors($validator)
                        ->withInput();
        }

        // バリデーション済みデータの取得
        $validated = $validator->validated();

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
