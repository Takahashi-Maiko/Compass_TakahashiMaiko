@extends('layouts.sidebar')

@section('content')
<!-- スクール予約画面 -->
<div class="vh-100 pt-5" style="background:#ECF1F6;">
  <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
    <div class="w-75 m-auto border" style="border-radius:5px;">

    <!-- ↓↓CalendarViewの各関数を利用してタイトルとカレンダー本体を分けて出力する。 -->
      <p class="text-center">{{ $calendar->getTitle() }}</p>
      <div class="">
        {!! $calendar->render() !!}
        <!-- ↑↑過去日グレーアウトさせたい 2024/8/25 -->
      </div>
    </div>


    <div class="text-right w-75 m-auto">
      <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
    </div>
  </div>
</div>

<!-- 予約キャンセル用のモーダルを作成する(2024/9/7) -->
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('deleteParts') }}" method="post">
      <div class="w-100">
        <div class="modal-inner-title w-50 m-auto">
          <p>予約日:<span class="reserve_day" name="setting_reserve"></span></p>
          <p>時間:<span class="reserve_day" name="reservePart"></span></p>
          <p>上記の予約をキャンセルしてもよろしいですか？</p>
          <!-- <input type="text" name="getPart" value="" form="deleteParts"> -->
          <!-- <input type="hidden" name="getPart[]" value="" form="deleteParts"> -->
        </div>
        <!-- <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
          <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
        </div> -->
        <div class="w-50 m-auto delete-modal-btn d-flex">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="delete-modal-hidden" name="user_name" value="">
          <input type="submit" class="btn btn-primary d-block" value="キャンセル">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>

@endsection
