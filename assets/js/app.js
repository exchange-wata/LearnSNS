$(function() {
  $(document).on('click', '.js-like', function() {
    var feed_id = $(this).siblings('.feed-id').text();
    var user_id = $('.signin-user').text();

    console.log(feed_id);
    console.log(user_id);

    var like_btn = $(this);
    var like_count = $(this).siblings('.like_count').text();


    $.ajax({
      // 送信先や送信するデータなど
      url: 'like_47.php',
      type: 'POST',
      datatype: 'json',
      data: {
      	// キー：値
        'feed_id': feed_id,
        'user_id': user_id
      }
    }).done(function (data) {
      // 成功時の処理
      if (data == 'true') {
      like_count--;
      like_btn.siblings('.like_count').text(like_count);

      like_btn.children('span').text('いいね！を取り消す');
      like_btn.addClass('js-unlike');
      like_btn.removeClass('js-like');
      like_btn.addClass('btn-info');
      like_btn.removeClass('btn-default');
        }
      console.log(data);
    }).fail(function (e) {
      // 失敗時の処理
      console.log(e);
    })

  });

  $(document).on('click', '.js-unlike', function() {
    var feed_id = $(this).siblings('.feed-id').text();
    var user_id = $('.signin-user').text();
    var like_btn = $(this);
    var like_count = $(this).siblings('.like_count').text();

    $.ajax({
      url: 'like_47.php',
      type: 'POST',
      datatype: 'json',
      data: {
        'feed_id': feed_id,
        'user_id': user_id,
        'is_unlike': true //$_POST['is_unlike'] = true;
      }
    }).done(function (data) {
      if (data == 'true') {
        like_count--;
        like_btn.siblings('.like_count').text(like_count);

        like_btn.children('span').text('いいね！');
        like_btn.addClass('js-like');
        like_btn.removeClass('js-unlike');
        like_btn.addClass('btn-default');
        like_btn.removeClass('btn-info');
      }
    }).fail(function (e) {
      console.log(e);
    })
  });

});
