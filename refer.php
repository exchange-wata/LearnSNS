<?php 

      // var_dump($stmt);


      // $record = $stmt->fetch(PDO::FETCH_ASSOC);
      // fetchで取得できるデータは一レコードずつ　→１fetch 1 record
      // echo "<pre>";
      // var_dump($record);
      // echo "</pre>";
      // <pre></pre> →改行や空白情報を保持したまま表示してくれる

      // fetchするごとに、次のレコードを指定する
      // $record = $stmt->fetch(PDO::FETCH_ASSOC);
      // echo "<pre>";
      // var_dump($record);
      // echo "</pre>";
      // データがなくなると、bool(false)と表示される　＝　0、何もない、という意味　→if文でfalseになったらwhile文をbreakする

      // exit();

      // 配列への要素追加構文
          // $arr = array();
          // $arr[] = 'hoge';
          
          // echo "<pre>";
          // var_dump($arr);
          // echo "</pre>";

          // echo $arr[0];
      

      // forとforeach
      $members = array('A','B','C','D','E');

      $c = count($members);

      // forは自分で繰り返し回数の基準を決めれる
      for ($i=0; $i < $c; $i++) { 
          echo "$members[$i]";
      }
        echo "<br>";

      // foreachは配列が必須(配列の要素数で回数が決まる)
      foreach ($members as $member) {
        // $member = $members[0]; ←0の部分は繰り上がる
        echo $member;
      }
      
 ?>