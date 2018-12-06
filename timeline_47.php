<?php
// selectの時必要のため、頭にこれがいる
    session_start();

// db connect
    // 処理を実行
    require('dbconnect.php');

    // 関数の宣言のみ。処理の実行はなし
    require('function.php');


// ------------サインインしとるユーザーの表示---------
// select→ users table $_SESSIONに保存されているidを使って一件だけ取り出す
    // $sql='SELECT * FROM `users` WHERE `id`=?';
    // $data=array($_SESSION['id']);
    // $stmt=$dbh->prepare($sql);
    // $stmt->execute($data);
  
// $signin_userに取り出したレコードを代入
    // $signin_user=$stmt->fetch(PDO::FETCH_ASSOC);


// ログイン済みかチェックし、未ログインであればログイン画面に戻す
    // if (!isset($_SESSION)["id"]) {
    //   header("Location:signin.php");
    //   exit();//このタイミングで処理を中断する
    // }

    // 任意のタイミングで処理を実行可能
    check_signin($_SESSION["id"]);
    // 戻り値がない場合、代入の形で書かなくて良い

    
    $signin_user=get_user($dbh,$_SESSION["id"]);



//---------- 投稿に関して-----------
    $errors = array();

    // ボタン押した時
    if(!empty($_POST)){
      $feed = $_POST['feed'];

      if ($feed == '') {
          $errors['feed'] = 'blank';
       }

      else {
        // 投稿があった時の処理
        require('dbconnect.php');
        $sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?, `created`=NOW()'; 
        $data = array($feed,$signin_user['id']);
        $stmt = $dbh->prepare($sql); 
        $stmt->execute($data);
          
        header("Location: timeline_47.php");
        exit();

      }

      }


//-----------pagingの処理------------
      if (isset($_GET['page'])) {
        $page = $_GET['page'];
      } else {
        $page = 1;
      }
 
      // 1ページあたりのページ数
      const CONTENT_PER_PAGE = 4;

      // -1などの不正な値を渡された時の対策
      $page = max($page,1);
      // スキップするレコード数を指定する
      // スキップするレコード数 = (指定ページ　- 1) * 表示件数
      $start = ($page -1) * CONTENT_PER_PAGE;

      // feedsテーブルのレコードを取得
      // COUNT() レコードの数を集計するSQL
      $sql_cnt = 'SELECT COUNT(*) AS `cnt` FROM `feeds`';
      $stmt_cnt = $dbh->prepare($sql_cnt);
      $stmt_cnt->execute();
      $result_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC);

      // 1ページあたりに表示する投稿件数を指定する  
      $sql_page = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` 
                  FROM `feeds` AS `f` 
                  LEFT JOIN `users` AS `u` 
                  ON `f`.`user_id` = `u`.`id` 
                  ORDER BY `f`.`created` DESC LIMIT ' . CONTENT_PER_PAGE .' OFFSET ' . $start;
      $stmt_page = $dbh->prepare($sql_page);
      $stmt_page->execute();
      $result_page = $stmt_page->fetch(PDO::FETCH_ASSOC);
      
      // 最後のページ数を取得
      // 最後のページ　＝取得したページ/1ページあたりのページ数
      $last_page = ceil($result_cnt['cnt']/CONTENT_PER_PAGE);

      // 最後のページより大きい値を渡された際の対策
      $page = min($page, $last_page);



//-------- 検索ボタンが押されたら曖昧検索----------
      // 検索ボタンが押されたら=GET送信されたsearch_wordというキーのデータを有する
      if (isset($_GET['search_word'])==true) {
          // 曖昧検索用SQL(like演算子を使う)
          $sql = 'select f.*, u.name, u.img_name 
                  from feeds as f 
                  left join users as u 
                  on f.user_id = u.id 
                  where f.feed like"%'.$_GET['search_word'].'%" 
                  order by f.created desc';
          // $sql = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` 
          //         FROM `feeds` AS `f` 
          //         LEFT JOIN `users` AS `u` 
          //         ON `f`.`user_id` = `u`.`id` 
          //         WHERE `f`.`feed` LIKE "%"?"%" 
          //         ORDER BY `f`.`created` DESC
          //         LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
          // $data = [$_GET['search_word']];
          // $data = [];
        }else{
          // 通常、(検索ボタンを押してない)は全権取得
          // LEFT JOINで全件取得
          $sql = "select f.*, u.name, u.img_name 
                  from feeds as f 
                  left join users as u 
                  on f.user_id = u.id 
                  where 1 order by created desc 
                  limit $start,$last_page";
      }
  
      $data = [];
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);
      

//-----------feedsの配列を用意-----------
      $feeds = array();
      
      while (true) {
          $record = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($record == false) {
              break;
          }

          // 
          // 
          //  
          // テーブルから今取得できているfeedに対してのデータを取得
          // $comment_sql='select c.*, u.name, u.img_name
          //               from comments as c
          //               left join users as u 
          //               on c.user_id=u.id
          //               where feed_id=?';

          $comment_sql = 'SELECT `c`.*, `u`.`name`, `u`.`img_name` 
                          FROM `comments` AS `c` 
                          LEFT JOIN `users` AS `u`  
                          ON `c`.`user_id` = `u`.`id` 
                          WHERE `c`.`feed_id` = ?';

          $comment_data = array($record["id"]);

          // sql文実行
          $comment_stmt = $dbh->prepare($comment_sql);
          $comment_stmt->execute($comment_data);

          $comments_array=array();


          while (true) {
            $comment_record=$comment_stmt->fetch(PDO::FETCH_ASSOC);

            if ($comment_record==false) {
              break;
            }

            // 取得したコメントのデータを追加代入(重要！)
            $comments_array[]=$comment_record;
          }

          // 一行文の変数(連想配列)に、新しくcommentsというキーを追加し、コメント情報を代入(超重要！)
          $record["comments"]=$comments_array;


          // いいね済みかどうか
          $like_flg_sql = 'SELECT * FROM `likes` WHERE `user_id` = ? AND `feed_id` = ?';
          $like_flg_data = [$signin_user['id'], $record['id']];
          $like_flg_stmt = $dbh->prepare($like_flg_sql);
          $like_flg_stmt->execute($like_flg_data);
          $is_liked = $like_flg_stmt->fetch(PDO::FETCH_ASSOC);
          // 三項演算子
          $record['is_liked'] = $is_liked ? true : false;

          // 何件いいねされているか確認
          $like_sql = 'SELECT COUNT(*) AS `like_cnt` FROM `likes` WHERE `feed_id` = ?';
          $like_data = [$record['id']];
          $like_stmt = $dbh->prepare($like_sql);
          $like_stmt->execute($like_data);
          $like = $like_stmt->fetch(PDO::FETCH_ASSOC);
          $record['like_cnt'] = $like['like_cnt'];
          
          // like数を取得
          // $like_flag=$like_flag_stmt->fetch(PDO::FETCH_ASSOC);
          // if ($like_flag["like_flag"]>0) {
          //     $record["like_flag"]=1;            
          // }
          // else{
          //     $record["like_flag"]=0;
          // }


          // いいね済みのリンクが押された時には、配列にすでにいいねしているものだけを代入する
          if (isset($_GET["feed_select"])&&($_GET["feed_select"]=="likes")&&($record["like_flag"]==1)) {
            $feeds[]=$record;
          }

          // feed_selectが指定されていない時は全件表示
          if (!isset($_GET["feed_select"])) {
            $feeds[]=$record;
          }

          // 新着順が押された時、全件表示
          if (isset($_GET["feed_select"])&&($_GET["feed_select"]=="news")) {
            $feeds[]=$record;
          }

      }
      
// echo $page;  

// echo "<pre>";
// var_dump($feeds);
// echo "</pre>";   
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>

<body style="margin-top: 60px; background: #E4E6EB;">
  <?php include("navbar.php"); ?>
  <span hidden class="signin-user"><?php echo $signin_user['id']; ?></span>
  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">

          <?php if (isset($_GET["feed_select"])&&($_GET["feed_select"]=="likes")) { ?>
              <li><a href="timeline_47.php?feed_select=news">新着順</a></li>
              <li class="active"><a href="timeline_47.php?feed_select=likes">いいね！済み</a></li>
              <!-- <li><a href="timeline_47.php?feed_select=follows">フォロー</a></li> -->
          <?php } else{ ?>
              <li class="active"><a href="timeline_47.php?feed_select=news">新着順</a></li>
              <li ><a href="timeline_47.php?feed_select=likes">いいね！済み</a></li>
              <!-- <li><a href="timeline_47.php?feed_select=follows">フォロー</a></li> -->
          <?php }?>
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
            <?php if(isset($errors['feed']) && $errors['feed'] == 'blank') { ?>
            <p class="alert alert-danger">投稿データを入力してください</p>
            <?php } ?>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
          </form>
        </div>


<!-- 繰り返し -->
        <?php foreach($feeds as $feed){ ?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed['img_name']; ?>" width="40">
              </div>
              <div class="col-xs-11">
                <?php echo $feed['name']; ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $feed['created']; ?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $feed['feed']; ?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">
                <span hidden class="feed-id"><?php echo $feed['id']; ?></span>
                <?php if ($feed['is_liked']):?>
                  <!-- is_likedにはtrueが入っている＝いいねされている -->
                    <button type="submit" class="btn btn-info btn-xs js-unlike">
                        <span>いいね！を取り消す</span>
                    </button>
                <?php else: ?>
                  
                  <button type="submit" class="btn btn-default btn-xs js-like">
                      <span>いいね！</span>
                  </button>
                <?php endif; ?>
                
                <!-- <span class="like_count">いいね数：<?php echo $feed["like_cnt"]; ?></span> -->
                
                
                <a href="#collapseComment<?php echo $feed["id"]; ?>" data-toggle="collapse" aria-expanded="false">

                  <?php if ($feed["comment_count"]==0) {?>
                  <span class="comment_count">コメント</span>
                  <?php }else{?>
                  <span class="comment_count">コメント数：<?php echo $feed["comment_count"]; ?></span>
                  <?php } ?>

                </a>
                


                <?php 
                    if ($feed["user_id"]==$_SESSION["id"]) {
                       
                      ?>
                  <a href="edit.php?feed_id=<?php echo $feed["id"]?>" class="btn btn-success btn-xs">編集</a>
                  <a onclick="return confirm('本当に削除しますか？');" href="delete.php?feed_id=<?php echo $feed["id"]?>" class="btn btn-danger btn-xs">削除</a>
                  <!-- 何を削除すべきかわからんくなるため、どのidのものを、削除更新するかを確かにする -->
                  <!-- onclickで削除の確認表示 -->
                <?php } ?>
     
              </div>
              <!-- コメントが押されたら表示される領域 -->
                <!-- <div class="collapse" id="collapseComment">
                    表示の確認              
                </div> -->
                <?php include("comment_view.php") ?>

            </div>
          </div>
          <?php } ?>
<!-- 繰り返し終了 -->

      <div aria-label="Page navigation">
          <ul class="pager">
        <?php if ($page == 1) {?>
          <li class="previous disabled">
            <a href="#">
              <span aria-hidden="true">&larr;</span>
              Newer
            </a>
          </li>
        <?php }else{ ?>
            <li class="previous">
              <a href="timeline_47.php?page=<?php echo $page - 1; ?>">
                <span aria-hidden="true">&larr;</span>
                Newer
              </a>
            </li>
        <?php } ?>

        <?php if ($page == $last_page) { ?>
            <li class="next disabled">
              <a href="#">Older
                <span aria-hidden="true">&rarr;</span>
              </a>
            </li>
        <?php }else{ ?>
            <li class="next">
              <a href="timeline_47.php?page=<?php echo $page + 1; ?>">Older
                <span aria-hidden="true">&rarr;</span>
              </a>
            </li>
        <?php } ?>
          </ul>
      </div>
    </div>
  </div>
  


  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
  <script src="assets/js/app.js"></script>
</body>
</html>
