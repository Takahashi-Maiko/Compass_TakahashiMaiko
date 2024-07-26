<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['guest']], function () {
    Route::namespace('Auth')->group(function () {
        Route::get('/register', 'RegisterController@registerView')->name('registerView');
        Route::post('/register/post', 'RegisterController@registerPost')->name('registerPost');
        Route::get('/login', 'LoginController@loginView')->name('loginView');
        Route::post('/login/post', 'LoginController@loginPost')->name('loginPost');
    });
});
Route::group(['middleware' => 'auth'], function () {
    Route::namespace('Authenticated')->group(function () {
        Route::namespace('Top')->group(function () {
            Route::get('/logout', 'TopsController@logout');
            Route::get('/top', 'TopsController@show')->name('top.show');
        });
        Route::namespace('Calendar')->group(function () {
            Route::namespace('General')->group(function () {
                Route::get('/calendar/{user_id}', 'CalendarsController@show')->name('calendar.general.show');//スクール予約
                Route::post('/reserve/calendar', 'CalendarsController@reserve')->name('reserveParts');
                Route::post('/delete/calendar', 'CalendarsController@delete')->name('deleteParts');
            });
            Route::namespace('Admin')->group(function () {
                Route::get('/calendar/{user_id}/admin', 'CalendarsController@show')->name('calendar.admin.show');//スクール予約確認
                Route::get('/calendar/{date}/{part}', 'CalendarsController@reserveDetail')->name('calendar.admin.detail');
                Route::get('/setting/{user_id}/admin', 'CalendarsController@reserveSettings')->name('calendar.admin.setting');//スクール枠登録
                Route::post('/setting/update/admin', 'CalendarsController@updateSettings')->name('calendar.admin.update');
            });
        });
        Route::namespace('BulletinBoard')->group(function () {
            Route::get('/bulletin_board/posts/{keyword?}', 'PostsController@show')->name('post.show');   //投稿一覧を表示する画面
            Route::get('/bulletin_board/input', 'PostsController@postInput')->name('post.input');
            Route::get('/bulletin_board/like', 'PostsController@likeBulletinBoard')->name('like.bulletin.board');   //いいねした投稿画面
            Route::get('/bulletin_board/my_post', 'PostsController@myBulletinBoard')->name('my.bulletin.board');   //自分の投稿画面
            Route::post('/bulletin_board/create', 'PostsController@postCreate')->name('post.create');   //新規投稿
            Route::post('/create/main_category', 'PostsController@mainCategoryCreate')->name('main.category.create');   //メインカテゴリー作成
            Route::post('/create/sub_category', 'PostsController@subCategoryCreate')->name('sub.category.create');   //サブカテゴリー作成
            Route::get('/bulletin_board/post/{id}', 'PostsController@postDetail')->name('post.detail');   //投稿の内容を表示する画面
            Route::post('/bulletin_board/edit', 'PostsController@postEdit')->name('post.edit');   //投稿編集
            Route::get('/bulletin_board/delete/{id}', 'PostsController@postDelete')->name('post.delete');   //投稿の削除
            Route::post('/comment/create', 'PostsController@commentCreate')->name('comment.create');   //コメント
            Route::post('/like/post/{id}', 'PostsController@postLike')->name('post.like');   //いいね
            Route::post('/unlike/post/{id}', 'PostsController@postUnLike')->name('post.unlike');   //いいね外す
        });
        Route::namespace('Users')->group(function () {
            Route::get('/show/users', 'UsersController@showUsers')->name('user.show');
            Route::get('/user/profile/{id}', 'UsersController@userProfile')->name('user.profile');
            Route::post('/user/profile/edit', 'UsersController@userEdit')->name('user.edit');
        });
    });
});
