<?php
session_start();

require('dbconnect.php');

$errors = array();

  if (!empty($_POST)) { 
      //POST送信があった時に以下を実行
      $email = $_POST['input_email'];
      $password = $_POST['input_password'];

    if($email == ''){
      $errors['email'] = 'blank';
    }

    if($password == ''){
      $errors['password'] = 'blank';
    }

    if ($email != '' && $password != '' ) {
      // データベースとの照合処理
      // データベースから取り出し
      $sql = 'SELECT * FROM `users` WHERE `email`=?';
      $data = array($email);
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);
      $record = $stmt->fetch(PDO::FETCH_ASSOC);

      // メールアドレスでの本人確認
      if ($record == false) {
        // 一致するレコードがなかった時
          $errors['signin'] = 'failed';
      }
      
      else {
        // 一致するメアドがあった時
        // // パスワードの一致確認
        //   $errors['signin'] = 'blank';

        if (password_verify($password,$record['password'])) {
          // ハッシュ化されたパスワードをさらに変換して一致するかどうかを確認する
          // 一致した時　＝　認証成功
          echo "<h1>認証成功</h1>";
          //SESSION変数にIDを保存
          $_SESSION['id'] = $record['id'];
          
          header("Location: timeline.php");
          exit();

        }

        else{
          // パスワードが不一致だった時
          $errors['signin'] = 'failed';

        }

        }


     }

  }

// var_dump($errors);

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
<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <div class="col-xs-8 col-xs-offset-2 thumbnail">
        <h2 class="text-center content_header">サインイン</h2>
        <form method="POST" action="" enctype="multipart/form-data">
          
          <div class="form-group">
            <label for="email">メールアドレス</label>
            <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
            <?php if(isset($errors['email']) && $errors['email'] == 'blank') { ?>
            <p class="text-danger">メールアドレスを正しく入力してください</p>
            <?php } ?>

          </div>
          
          <div class="form-group">
            <label for="password">パスワード</label>
            <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
            <?php if(isset($errors['password'])&& $errors['password'] == 'blank') { ?>
              <p class="text-danger">パスワードを正しく入力してください</p>
            <?php } ?>
            <?php if(isset($errors['signin']) && $errors['signin'] == 'failed') { ?>
              <p class="text-danger">サインインに失敗しました</p>
            <?php } ?>
          </div>
          <input type="submit" class="btn btn-info" value="サインイン">
        </form>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>

