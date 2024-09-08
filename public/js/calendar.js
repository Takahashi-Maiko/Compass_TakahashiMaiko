$(function () {

  // ↓↓投稿編集用のモーダルを予約キャンセル仕様に変更する(2024/9/7)
  $('.delete-modal-open').on('click', function () {
    $('.js-modal').fadeIn();
    var getPart = $(this).attr('getPart[]');
    var reservePart = $(this).attr('reservePart');
    // var post_id = $(this).attr('post_id');
    // $('.modal-inner-title input').val(post_title);
    // $('.modal-inner-body textarea').text(post_body);
    $('.delete-modal-hidden').val(getPart);
    return false;
  });

});
