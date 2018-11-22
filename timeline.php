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
          
        header("Location: timeline.php");
        exit();

      }

      }


//-----------pagingの処理------------
      
      // ページ番号が入る変数
      // $page=''; 
      // 1ページあたりに表示するデータの数
      // $page_row_number=5;
      const CONTENT_PER_PAGE = 5;


      if (isset($_GET['page'])) {
        $page=$_GET['page'];          
      }
      else{
        // get送信されているページがない場合、1ページ目とみなす
        $page=1;
      }

  // インターン中のやつ(timeline_page.php)

      // これと同じことをしている関数
      // if ($page<0) {
      //   $page=1;
      // }
      // max:カンマ区切りで羅列された数字の中から最大数を返す=$pageとカンマ後の数字を比較
      $page=max($page,1);
      $sql_count = "SELECT COUNT(*) AS `cnt` FROM `feeds`";
      $stmt_count = $dbh->prepare($sql_count);
      $stmt_count->execute();
      $record_cnt = $stmt_count->fetch(PDO::FETCH_ASSOC);
   
      // 最後のページ数を取得
      // 最後のページ　＝取得したページ/1ページあたりのページ数
      $last_page = ceil($record_cnt['cnt']/CONTENT_PER_PAGE);

      // 最後のページより大きい値を渡された際の対策
      $page = min($page, $last_page);

      $start = ($page -1) * CONTENT_PER_PAGE;

      // feedsテーブルのレコードを取得
      // COUNT() レコードの数を集計するSQL
      // $sql = 'SELECT COUNT(*) AS `cnt` FROM `feeds`';
      $sql = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` 
                      FROM `feeds` AS `f` 
                      LEFT JOIN `users` AS `u` 
                      ON `f`.`user_id` = `u`.`id` 
                      ORDER BY `f`.`created` DESC LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
      $stmt = $dbh->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      // $cnt = $result['cnt'];




//-------- 検索ボタンが押されたら曖昧検索----------
      // 検索ボタンが押されたら=GET送信されたsearch_wordというキーのデータを有する
      if (isset($_GET['search_word'])==true) {
          // 曖昧検索用SQL(like演算子を使う)
          // $sql = 'select f.*, u.name, u.img_name 
          //         from feeds as f 
          //         left join users as u 
          //         on f.user_id = u.id 
          //         where f.feed like"%'.$_GET['search_word'].'%" 
          //         order by f.created desc';
          $sql = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` 
                  FROM `feeds` AS `f` 
                  LEFT JOIN `users` AS `u` 
                  ON `f`.`user_id` = `u`.`id` 
                  WHERE `f`.`feed` LIKE "%"?"%" 
                  ORDER BY `f`.`created` DESC
                  LIMIT '. CONTENT_PER_PAGE .' OFFSET ' . $start;
          $data = [$_GET['search_word']];
        }

      else{
      
          // 通常、(検索ボタンを押してない)は全権取得
          // LEFT JOINで全件取得
          $sql = "select f.*, u.name, u.img_name 
                  from feeds as f 
                  left join users as u 
                  on f.user_id = u.id 
                  where 1 order by created desc 
                  limit $start,$last_page";
          $data = [];
      }
  
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);
      

//-----------feedsの配列を用意-----------
      $feeds = array();
      
      while (true) {
          $record = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($record == false) {
              break;
          }

          // comment テーブルから今取得できているfeedに対してのデータを取得
          $comment_sql='select c.*, u.name, u.img_name
                        from comments as c
                        left join users as u 
                        on c.user_id=u.id
                        where feed_id=?';
          $comment_data=array($record["id"]);

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


          // like数を取得するSQL文を作成
          $like_sql="SELECT COUNT(*) AS `like_cnt` FROM `likes` WHERE `feed_id`=?";
          $like_data = array($record["id"]);          
          

          // SQL文を実行
          $like_stmt = $dbh->prepare($like_sql);
          $like_stmt->execute($like_data);


          // like数を取得
          $like=$like_stmt->fetch(PDO::FETCH_ASSOC);
          // $like=array("like_cnt"=>5);
          
          $record["like_cnt"]=$like["like_cnt"];

          

          // like済みか判断するSQLを作成
          $like_flag_sql='SELECT count(*) as `like_flag` FROM `likes` WHERE `user_id`=? and `feed_id`=?';
          
          // SQL文を実行
          $like_flag_data = array($_SESSION["id"],$record["id"]);          
          
          $like_flag_stmt = $dbh->prepare($like_flag_sql);
          $like_flag_stmt->execute($like_flag_data);

          // like数を取得
          $like_flag=$like_flag_stmt->fetch(PDO::FETCH_ASSOC);
          if ($like_flag["like_flag"]>0) {
              $record["like_flag"]=1;            
          }
          else{
              $record["like_flag"]=0;
          }


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

          // $feeds[] = $record;
         
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

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">

          <?php if (isset($_GET["feed_select"])&&($_GET["feed_select"]=="likes")) { ?>
              <li><a href="timeline.php?feed_select=news">新着順</a></li>
              <li class="active"><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
              <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
          <?php } else{ ?>
              <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
              <li ><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
              <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
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
                <!-- <form method="POST" action="" style="display: inline;">
                  <input type="hidden" name="feed_id" >
                  
                    <input type="hidden" name="like" value="like"> -->

                    <?php if ($feed["like_flag"]==0) {?>
                    <a href="like.php?feed_id=<?php echo $feed["id"]; ?>">
                    <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！</button></a>
                    <?php }else{?>
                      <a href="unlike.php?feed_id=<?php echo $feed["id"]; ?>">
                    <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-down" aria-hidden="true"></i>いいね！を取り消す</button></a>
                    <?php }?>
                <!-- </form> -->

                <?php if ($feed["like_cnt"]>0) { ?>
                <span class="like_count">いいね数：<?php echo $feed["like_cnt"]; ?></span>
                <?php } ?>  

                
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
        <?php if ($page==1) {?>
          <li class="previous disabled">
            <a href="timeline.php?page=<?php echo $page-1; ?>">
              <span aria-hidden="true">&larr;</span>
              Newer
            </a>
          </li>
        <?php }else{ ?>
            <li class="previous">
              <a href="timeline.php?page=<?php echo $page-1; ?>">
                <span aria-hidden="true">&larr;</span>
                Newer
              </a>
            </li>
        <?php } ?>

        <?php if ($page==$last_page) { ?>
            <li class="next disabled">
              <a href="#">Older
                <span aria-hidden="true">&rarr;</span>
              </a>
            </li>
        <?php }else{ ?>
            <li class="next">
              <a href="timeline.php?page=<?php echo $page+1; ?>">Older
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
</body>
</html>
