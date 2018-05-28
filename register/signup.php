<?php
  // PHPプログラム

  session_start();

  $errors = array(); //この配列の意味→エラーの種類を空として定義

  // 以下、確認ボタンが押された時の処理
  if (!empty($_POST)) { //POST送信があった時に以下を実行
      $name = $_POST['input_name'];
      $email = $_POST['input_email'];
      $password = $_POST['input_password'];
      // 文字数チェック
      $countname = strlen($password);
      $count = strlen($name);

      // ユーザー名の空チェック→html内の対応する処理へ飛ぶ
      if ($name == '') {
          $errors['name'] = 'blank';
       }

      else {
          require('../dbconnect.php');

          $sql = 'SELECT COUNT(*) as `cnt` FROM `users` WHERE `name`=?';
          $data = array($name);
          $stmt = $dbh->prepare($sql);
          $stmt->execute($data);

          $dbh = null;

          $rec = $stmt->fetch(PDO::FETCH_ASSOC);

          var_dump($rec);
      }
        
      if ($rec['cnt'] > 0) {
        // 名前の数が0より大きい＝すでに登録がある
        $errors['name'] = 'duplicate' ;
      }


      elseif ($countname < 4 || 16 < $countname) {
        $errors['name'] = 'length';

      }

      if ($email == ''){
         $errors['email'] = 'blank';   

       }
      
      // mail address の重複時エラーが出るようにする
      // if か else か、なので、ifの中にelseを入れない
      else {
          require('../dbconnect.php');

          $sql = 'SELECT COUNT(*) as `cnt` FROM `users` WHERE `email`=?';
          $data = array($email);
          $stmt = $dbh->prepare($sql);
          $stmt->execute($data);

          $dbh = null;

          $rec = $stmt->fetch(PDO::FETCH_ASSOC);

          var_dump($rec);
      if ($rec['cnt'] > 0) {
        // メアドの数が0より大きい＝すでに登録がある
        $errors['email'] = 'duplicate' ;
      }

      }
        


        
      if ($password == '') {
          $errors['password'] = 'blank';
      }

      elseif ($count < 4 || 16 < $count) {
        $errors['password'] = 'length';

      }

      // 画像は$_POSTで送信データを受け取れない→ファイルアップロード専用のスーパーグローバル変数$_FILESを使用
      $file_name = $_FILES['input_img_name']['name'];

        if (!empty($file_name)){
            //拡張しチェック
            $file_type = substr($file_name, -4);
            // 画像名の後ろから三文字とる
            $file_type = strtolower($file_type);
            // 比較するために取得した拡張子を全て小文字に変換
            if ($file_type != '.jpg' && $file_type != '.png' && $file_type != '.gif' && $file_type != 'jpeg'){
              // .を増やして四文字扱いに
              // エラーの処理
              $errors['img_name'] = 'type';
                }
            }
            else{
              //ファイルがないときの処理
            $errors['img_name'] = 'blank';

            }
          // echo $file_name . '<br>';

          // echo "<pre>";
          // var_dump($_FILES);
          // echo "</pre>";

          if (empty($errors)){
            // エラーがないときの処理 = 全部問題なく入力された時の処理
            date_default_timezone_set('Asia/Manila'); 
            // フィリピン時間にする
            $date_str = date('YmdHis'); 
            // YmdHis→年月日＋秒までを指定することで取得フォーマットを指定
            $submit_file_name = $date_str . $file_name;
            // echo $date_str;
            // echo "<br>";
            // echo $submit_file_name;

            move_uploaded_file($_FILES['input_img_name']['tmp_name'], '../user_profile_img/' . $submit_file_name);
              // '../user_profile_img/' . $submit_file_name と文字連結をすることで
              // ../user_profile_img/20170825071820hiroshi.jpg のような保存先を指定しています。

            $_SESSION['register']['name'] = $_POST['input_name'];
            $_SESSION['register']['email'] = $_POST['input_email'];
            $_SESSION['register']['password'] = $_POST['input_password'];
            // 上記3つは$_SESSION['register'] = $_POST;という書き方で1文にまとめることもできます
            $_SESSION['register']['img_name'] = $submit_file_name;
            $_SESSION['sonota']['name'] = '適当';


              header('Location: check.php');
              exit();
              // 送信されたデータを入力情報確認のページへ


            }

   }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="../assets/css/style.css"> <!-- 追加 -->

</head>

<body style="margin-top: 60px">
  <div class="container">
    <div class="row">
      <!-- ここにコンテンツ -->
        <!-- ここから -->
        <div class="col-xs-8 col-xs-offset-2 thumbnail">
          <h2 class="text-center content_header">アカウント作成</h2>
          <form method="POST" action="signup.php" enctype="multipart/form-data">
            <!-- 画像データを扱うためのコード：enctypeからデータの””までのとこ -->
           
            <div class="form-group">
              <label for="name">ユーザー名</label>
              <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎">
              <?php if(isset($errors['name']) && $errors['name'] == 'blank') { ?>
              <!-- isset:これの後ろにくるものがセットされているか。isなんとかは確認のため。 -->
              <p class="text-danger">ユーザー名を入力してください</p>
              <?php } ?>
              <?php 
              if (isset($errors['name']) && $errors['name'] == 'length') {?>
                 <p class="text-danger">ユーザー名は4 ~ 16文字で入力してください</p>
                 <?php 
               } 
               ?>
             <?php 
              if (isset($errors['name']) && $errors['name'] == 'duplicate') {?>
                 <p class="text-danger">このユーザー名は既に使われています。別のユーザー名を入力して下さい。</p>
                 <?php 
               } 
               ?>

 
            </div>
           
            <div class="form-group">
              <label for="email">メールアドレス</label>
              <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
                <?php if(isset($errors['email']) && $errors['email'] == 'blank') { ?>
                <p class="text-danger">メールアドレスを入力してください</p>
              <?php } ?>
                <?php if(isset($errors['email']) && $errors['email'] == 'duplicate') { ?>
                <p class="text-danger">このメールアドレスは既に使われています。別のメールアドレスを入力して下さい。</p>
              <?php } ?>

            
            </div>
           
            <div class="form-group">
              <label for="password">パスワード</label>
              <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
              <!-- type="password":パスワードを伏字で表示 -->
                <?php if(isset($errors['password']) && $errors['password'] == 'blank') { ?>
                <p class="text-danger">パスワードを入力してください</p>
              <?php } ?>
              <?php 
              if (isset($errors['password']) && $errors['password'] == 'length') {?>
                 <p class="text-danger">パスワードは4 ~ 16文字で入力してください</p>
                 <?php 
               } 
               ?>

            </div>
           
            <div class="form-group">
              <label for="img_name">プロフィール画像</label>
              <input type="file" name="input_img_name" id="img_name" accept="iamage/*">
              <?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank') { ?>
              <p class="text-danger">画像を選択してください</p>
              <?php } ?>
                <?php if(isset($errors['img_name']) && $errors['img_name'] == 'type') { ?>
              <p class="text-danger">拡張子が「jpg」「png」「gif」の画像を選択してください</p>
              <?php } ?>


            </div>
           
            <input type="submit" class="btn btn-default" value="確認">
            <a href="../signin.php" style="float: right; padding-top: 6px;" class="text-success">サインイン</a>
          </form>
        </div>
      <!-- ここまで -->

    </div>
  </div>
  <script src="../assets/js/jquery-3.1.1.js"></script>
  <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="../assets/js/bootstrap.js"></script>
</body>
</html>
