$(function () {

  // ↓↓投稿編集用のモーダルを予約キャンセル仕様に変更する(2024/9/7)
  $('.delete-modal-open').on('click', function () {  //キャンセルするときにクリックされた時
    $('.js-modal').fadeIn();         //モーダルを表示する
    var setting_reserve = $(this).attr('setting_reserve');   //予約日時の取得
    var reservePart = $(this).attr('reservePart');   //予約枠(リモ1・2・3のどの枠を選択しているか)の取得
    // var post_id = $(this).attr('post_id');
    $('.modal-inner-title input').val(getPart);
    // $('.modal-inner-body textarea').text(post_body);
    $('.delete-modal-hidden').val(reserveID);   //予約IDを隠す
    return false;
  });

});
