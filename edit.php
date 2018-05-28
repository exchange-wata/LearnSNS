<?php
  require('dbconnect.php');
  

  //feed_idを取得
  $feed_id=$_GET["feed_id"];

  // 更新ボタンが押された時(POST送信されたデータが存在したら)
  if (!empty($_POST)) {
  	// Updata文でDBに保存
  	// UPDATA テーブル名 SET　カラム名＝値、（,カラム名2=値2） WHERE 条件
  	$update_sql="update `feeds` 
  				set `feed`=? 
  				where `feeds`.`id`=?";
	  $data = array($_POST['feed'],$feed_id);

  	// SQL文実行
    $stmt = $dbh->prepare($update_sql);
    $stmt->execute($data);


  	// 一覧に戻る
  	header("Location:timeline.php");
  }



  // 編集したいfeeds tableのデータを取得し、入力欄に初期表示する
  // ポイント　→ 書いた人の情報も表示したいので、テーブル結合を行う(timeline.phpと同じもの)
  // 編集したいfeeds tableのデータは一件だけ(繰り返し処理は不必要)


  // SQL文
  $sql = "select f.*, u.name, u.img_name from feeds as f left join users as u on f.user_id = u.id where f.id=$feed_id";

  // SQL文実行
  $data = array();
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  $feeds = array();

  // フェッチ(データ取得)
  // $record = $stmt->fetch(PDO::FETCH_ASSOC);
  // $feeds = $record;
  // $feeds[0]に取得できた連想配列を代入する
  // $feeds[0]['name']で名前の表示が可能


  $feed=$stmt->fetch(PDO::FETCH_ASSOC);
  // $feed['name']で名前の表示が可能

  // HTML内にデータ表示の処理を取得



?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
  <link rel="stylesheet" type="text/css" href="edit.css">
</head>

<body style="margin-top: 60px;">
  <div class="container">
      <div class="row">
        <!-- ここにコンテンツの内容 -->
          <div class="col-xs-4 col-xs-offset-4">
              <form class="form-group" method="post">
                  <div class="col-xs-offset-2 col-xs-11">
                    <img src="user_profile_img/<?php echo $feed['img_name']; ?>" width="60" >
                        <p><?php echo $feed['name']; ?></p>
                        <p><?php echo $feed['created']; ?></p>
                  </div>
                  <textarea name="feed" class="form-control"><?php echo $feed['feed']; ?></textarea><br>
                  <input type="submit" value="更新" class="btn btn-primary btn-xs col-xs-offset-2 col-xs-8">



              </form>


          </div>  
          


      </div>




  </div> 
  


  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>

</body>


</html>
