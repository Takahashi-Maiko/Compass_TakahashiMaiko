$(function () {

  // ↓↓投稿編集用のモーダルを予約キャンセル仕様に変更する(2024/9/7)
  $('.delete-modal-open').on('click', function () {  //キャンセルするときにクリックされた時
    $('.js-modal').fadeIn();         //モーダルを表示する
    // ↓↓送られたデータを変数に格納する
    var reserveDate = $(this).attr('reserveDate');   //予約日時の取得
    var reservePart = $(this).attr('reserveParts');   //予約枠(リモ1・2・3のどの枠を選択しているか)の取得
    console.log('予約日時', reserveDate);
    console.log('予約パート', reservePart);
    if (reservePart == 'リモ1部') {
      reservePartNumber = "1";
    } else if (reservePart == 'リモ2部') {
      reservePartNumber = "2";
    } else if (reservePart == 'リモ3部') {
      reservePartNumber = "3";
    }
    // ↓↓送られてきたデータをモーダルに送る
    // ①データを値として送る
    $('.get_date').val(reserveDate);
    $('.get_part').val(reservePartNumber);
    // ②textと設定して送る
    $('.reserve_day').text(reserveDate);
    $('.reserve_part').text(reservePart);

    // // var post_id = $(this).attr('post_id');
    // $('.modal-inner-title input').val(getPart);
    // // $('.modal-inner-body textarea').text(post_body);
    // $('.delete-modal-hidden').val(reserveID);   //予約IDを隠す
    return false;
  });

});
