@extends('layouts.sidebar')

@section('content')
<div class="board_area w-100 border m-auto d-flex">
  <div class="post_view w-75 mt-5">
    <p class="w-75 m-auto">投稿一覧</p>
    @foreach($posts as $post)
    <div class="post_area border w-75 m-auto p-3">
      <p><span>{{ $post->user->over_name }}</span><span class="ml-3">{{ $post->user->under_name }}</span>さん</p>
      <p><a href="{{ route('post.detail', ['id' => $post->id]) }}">{{ $post->post_title }}</a></p>
      <div class="post_bottom_area d-flex">
        <div class="d-flex post_status">
          <!-- ↓↓コメント数の表示(2024/7/21) -->
          <div class="mr-5">
            <i class="fa fa-comment"></i><span class="comments_counts{{ $post->id }} ">{{ $post->postComments()->count() }}</span>
          </div>
          <!-- ↓↓いいね数の表示(2024/720) -->
          <div>
            @if(Auth::user()->is_Like($post->id))
            <p class="m-0"><i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likeCount() }}</span></p>
            @else
            <p class="m-0"><i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i><span class="like_counts{{ $post->id }}">{{ $post->likeCount() }}</span></p>
            @endif
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>
  <!-- ↓↓投稿検索エリア -->
  <div class="other_area border w-25">
    <div class="border m-4">
      <div class="post-btn"><a href="{{ route('post.input') }}">投稿</a></div>
      <div class="search-area">
        <input type="text" class="keyword-box" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
        <input type="submit" class="keyword-btn" value="検索" form="postSearchRequest">
      </div>
      <input type="submit" name="like_posts" class="category_btn like_btn" value="いいねした投稿" form="postSearchRequest">
      <input type="submit" name="my_posts" class="category_btn mypost_btn" value="自分の投稿" form="postSearchRequest">

 <ul class="accordion-menu">
        @foreach($categories as $category)
            <li class="accordion-item">
                <div class="accordion-header main_categories" category_id="{{ $category->id }}">
                    {{ $category->main_category }}
                </div>
                <ul class="accordion-content category_num{{ $category->id }}">
                    @foreach($category->subCategory as $sub_category)
                        <li>{{ $sub_category->sub_category }}</li>
                    @endforeach
                </ul>
            </li>
        @endforeach
    </ul>

        <!-- <ul>
          foreach($categories as $category) -->
        <!-- <li class="main_categories" category_id="{{ $category->id }}"><span>{{ $category->main_category }}<span></li> -->
        <!-- <li class="main_categories" category_id="{{ $category->id }}"><input type="submit" class="" value="{{ $category->main_category }}"></li>
          foreach($category->subCategory as $sub_category) -->
            <!-- <li class="sub_category" category_id="{{ $sub_category->id }}"><span>{{ $sub_category->sub_category }}<span></li> -->
            <!-- ↓↓サブカテゴリーに紐づいた投稿を表示させるためinputタグ使用 2024/8/18 -->
            <!-- <li class="sub_category" category_id="{{ $sub_category->id }}"><input type="submit" name="sub_category_posts" class="category_btn" value="{{ $sub_category->sub_category }}" form="postSearchRequest"></li>
          endforeach
        endforeach
      </ul> -->
    </div>
  </div>
  <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>
</div>
@endsection
