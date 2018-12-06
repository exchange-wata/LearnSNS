<?php 
    session_start();

  // DB接続
    require('dbconnect.php');

    require('function.php');
        $signin_user=get_user($dbh,$_SESSION["id"]);


  // ユーザーの一覧を表示させるため取得する

      // SQL文の作成
        $sql = 'SELECT * FROM `users` WHERE 1';
        
      // SQL実行
        $data = array();
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        $feeds = array();
      
      

      // 繰り返し文の中でフェッチ(配列に保存)
        while (true) {
              $record = $stmt->fetch(PDO::FETCH_ASSOC);
              if ($record == false) {
                  break;
              }
              // $feeds[]=$record;
        

          // コメント数を取得するSQL文を作成
          $feed_sql="SELECT COUNT(*) AS `feed_cnt` 
                     FROM `feeds`
                     where `user_id`=?";

          // 今回の$record["id"]はusers_idのもの
          // timeline.phpのものと同じだが、別物
          $feed_data = array($record["id"]); 

        
          // SQL文を実行
          $feed_stmt = $dbh->prepare($feed_sql);
          $feed_stmt->execute($feed_data);


          // like数を取得
          $feed_cnt=$feed_stmt->fetch(PDO::FETCH_ASSOC);
          
          $record["feed_cnt"]=$feed_cnt["feed_cnt"];



          // 配列を追加代入する
          $feeds[]=$record;

          }

  

      // ↓データ保存した配列を表示で使用する

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
      <div class="col-xs-12">

    <?php foreach($feeds as $feed){ ?>    
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed['img_name']; ?>" width="80" class="user">
              </div>
              <div class="col-xs-11"><a href="profile.php?user_id=<?php echo $feed['id']; ?>">
                <?php echo $feed['name']; ?></a><br>
                <span style="color: #7F7F7F;"><?php echo $feed['created']; ?></span>
              </div>
            </div>
            
            <div class="row feed_sub">
              <div class="col-xs-12">
                <span class="comment_count">つぶやき数 : <?php echo $feed["feed_cnt"]; ?></span>
              </div>
            </div>
          </div><!-- thumbnail -->
          <?php }?>
      </div><!-- class="col-xs-12" -->
    </div><!-- class="row" -->
  </div><!-- class="cotainer" -->
</body>
</html>