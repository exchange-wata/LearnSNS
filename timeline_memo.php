<?php

// selectの時に必要なため、頭にsession_start();がいる

// db connect

//select
    // nav_barにログインしとるユーザーの情報を表示させるためのsql
// $signin_userに取り出したレコードを代入

// 写真と名前を取り出す
// $image_name に写真のファイル名を代入
// $name に名前を表示
// var_dump($signin_user);
    // echo $signin_user['name']; 
    // echo $signin_user['img_name'];


// $errorsの配列を作る

// 投稿ボタンを押した時の処理
/*    if(!empty($_POST)){
      $feed = $_POST['feed'];
      // $_POST['feed'] →feedの理由　→inputやtextareaのnameタグと一致させるため
      //form 
      // ユーザー名の空チェック→html内の対応する処理へ飛ぶ、エラーを表示
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
          
        // $dbh = null;
        
        // クローンからサーバーにリクエストを送る感じ
        header("Location: timeline.php");
        exit();

      }

      }
*/

/*
      // LEFT JOINで全件取得
      // name変更した時に便利。別で扱う必要があるものは別テーブルに作る。
      // 正規形
      $sql = 'select f.*, u.name, u.img_name from feeds as f left join users as u on f.user_id = u.id where 1 order by created desc';
      $data = array();
      // arrayの中は$sqlの？の数と一致すれば良い
      $stmt = $dbh->prepare($sql);
      $stmt->execute($data);
      // stmt = statement
      // executeで取得したタイミングでは、 Object型をArray型に変換する　→PDOではfetch()を使用する

      // 表示用の配列を初期化
      $feeds = array();
      // これをwhile文にいれてしまうと、毎回初期化してしまって、何も入らんごとなる
      

      while (true) {
          $record = $stmt->fetch(PDO::FETCH_ASSOC);
          if ($record == false) {
              break;
          }
          $feeds[] = $record;
           
      }

 */

    echo 'fetchの処理についての説明';
    // $record = $stmt->fetch(PDO::FETCH_ASSOC);
    // fetchで取得できるデータは1レコードずつ
    // 1 fetch 1 record.
    // echo '<pre>';
    // var_dump($record);
    // echo '</pre>';

    // // fetchするごとに次のレコードを指定する
    // $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>';
    // var_dump($record);
    // echo '</pre>';

    // $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>';
    // var_dump($record);
    // echo '</pre>';

    // $record = $stmt->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>';
    // var_dump($record); // false
    // echo '</pre>';


    // データ型(数) { [キー]=> データ型(数) 値}
    // array(1) { ["id"]=> string(1) "1" }
    
    
    echo 'データ型';
    //   文字列型 String (str)
    //   整数型 Integer (int)
    //   浮動小数点型 Float
    //   配列型 Array (arr)
    //   論理型 Boolean (bool)


    echo '配列の使い方';
    // 配列への要素追加
    // $arr = array();
    // $arr[] = 'hoge';

    // echo '<pre>';
    // var_dump($arr);
    // echo '</pre>';

    // $arr[] = 'fuga';

    // echo '<pre>';
    // var_dump($arr);
    // echo '</pre>';

    // 配列の要素上書き
    // $arr[1] = 'moge';

    // echo $arr[0];
    // echo '<br>';
    // echo $arr[1];
    // echo '<br>';
    // echo $arr[2];

    // $shirohige = array('白ひげ' , 'エース');
    // $mugiwara = array('ルフィ', 'ゾロ');

    // $character = 'センゴク';

    // $kaizoku = array($shirohige, $mugiwara);
    // $kaigun = array($character);

    // $onepiece = array($kaizoku, $kaigun);

    // echo '<pre>';
    // var_dump($onepiece);
    // echo '</pre>';

    // echo $onepiece[0][1][1];

    // exit();