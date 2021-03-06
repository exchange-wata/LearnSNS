<?php 
  session_start();

  require('dbconnect.php');
  require('function.php');
      $signin_user=get_user($dbh,$_SESSION["id"]);



    // $sql = 'SELECT * FROM `users` WHERE `id`=?';
    // $data = array($_GET['user_id']);
    // $stmt = $dbh->prepare($sql);
    // $stmt->execute($data);
    // $profile_user =  $stmt->fetch(PDO::FETCH_ASSOC);

  if (isset($_GET["user_id"])) {
    // http://localhost/batch41_Web/LearnSNS/profile.php?user_id=14
    // 上記のようなURLでアクセスされた時は、$_GET["user_id"]に13が入る
    $user_id=$_GET["user_id"];
    }

    else{
    // http://localhost/batch41_Web/LearnSNS/profile.php
    // 上記のようなURLでアクセスされた時は、$_GET["user_id"]が存在しないので、$_SESSION["user_id"]を使用する
    $user_id=$_SESSION["id"];

    }

    // $sql = 'SELECT * FROM `users` WHERE `id`=?';
    // $data = array($user_id);
    // $stmt = $dbh->prepare($sql);
    // $stmt->execute($data);
    
    // $profile_user =  $stmt->fetch(PDO::FETCH_ASSOC);

    $profile_user=get_user($dbh,$_GET['user_id']);

   

    // Follower一覧の取得
    // 今表示されているプロフィールページの人のフォロワー
    $follower_sql='select fw.*, u.name, u.img_name, u.created
                    from followers as fw
                    left join users as u
                    on fw.follower_id=u.id
                    where user_id=?';

    $follower_data = array($user_id);
    $follower_stmt = $dbh->prepare($follower_sql);
    $follower_stmt->execute($follower_data);

    $followers=array();

    // ログインユーザーが今見ているプロフィールページの人をフォローしてたら1,フォローしてなかったら0
    $follow_flag=0;

    while(true){
        $follower_record =  $follower_stmt->fetch(PDO::FETCH_ASSOC);

        if ($follower_record==false) {
          break;
        }

        // 任意のフォロワーの中に、ログインしている人とフォロワーが一致しているかをチェック->重複していたらフォロー解除のボタン表示
        if ($follower_record["follower_id"]==$_SESSION["id"]) {
          $follow_flag=1;
        }

        $followers[]=$follower_record;

    }

    // Followingの表示
    $following_sql='select fw.*, u.name, u.img_name, u.created
                    from followers as fw
                    left join users as u
                    on fw.user_id=u.id
                    where follower_id=?';

    $following_data = array($user_id);
    $following_stmt = $dbh->prepare($following_sql);
    $following_stmt->execute($following_data);

    $followings=array();

    while(true){
        $following_record =  $following_stmt->fetch(PDO::FETCH_ASSOC);

        if ($following_record==false) {
          break;
        }

        $followings[]=$following_record;

    }    

    // var_dump($profile_user);
  

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

    <!-- ユーザー -->
    <div class="row">
      <div class="col-xs-3 text-center">
        <img src="user_profile_img/<?php echo $profile_user['img_name']; ?>" class="img-thumbnail" />
        <h2><?php echo $profile_user['name']; ?></h2>
        
        <?php if ($user_id!=$_SESSION["id"]) { ?>
          <?php if($follow_flag==0){?>
            <a href="follow.php?user_id=<?php echo $profile_user["id"]; ?>">
            <button class="btn btn-default btn-block">フォローする</button></a>

          <?php }else{ ?>
            <a href="unfollow.php?user_id=<?php echo $profile_user["id"]; ?>">
            <button class="btn btn-default btn-block">フォローを解除する</button></a>
          <?php } ?>
        <?php } ?>

      </div>

      <div class="col-xs-9">
        <ul class="nav nav-tabs">
          <li class="active">
            <a href="#tab1" data-toggle="tab">Following</a>
          </li>
          <li>
            <a href="#tab2" data-toggle="tab">Followers</a>
          </li>
        </ul>


        <!--タブの中身-->
        <div class="tab-content">
          <div id="tab1" class="tab-pane fade in active">

            <?php foreach ($followings as $following) {?>
            <div class="thumbnail">
              <div class="row">
                <div class="col-xs-2">
                  <img src="user_profile_img/<?php echo $following['img_name']; ?>" width="80">
                </div>
                <div class="col-xs-10">
                  名前 <?php echo $following["name"]; ?><br>
                  <a href="#" style="color: #7F7F7F;"><?php echo $following["created"]; ?>からメンバー</a>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>


          <div id="tab2" class="tab-pane fade">
            <?php foreach ($followers as $follower) {?>
            <div class="thumbnail">
              <div class="row">
                <div class="col-xs-2">
                  <img src="user_profile_img/<?php echo $follower['img_name']; ?>" width="80">
                </div>
                <div class="col-xs-10">
                  名前 <?php echo $follower["name"]; ?><br>
                  <a href="#" style="color: #7F7F7F;"><?php echo $follower["created"]; ?>からメンバー</a>
                </div>
              </div>
            </div>
            <?php } ?>
          </div>
        </div>

      </div><!-- class="col-xs-12" -->

    </div><!-- class="row" -->
  </div><!-- class="cotainer" -->
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>