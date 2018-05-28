<?php 
// DBに接続
  require('dbconnect.php');

// feed_idを取得
	$feed_id=$_GET["feed_id"];

// DELETE文　条件必須
  	$sql="DELETE FROM `feeds` 
  		  WHERE `feeds`.`id`=?";
	$data = array($feed_id);

// SQL実行
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);


// 一覧に戻る
  	header("Location:timeline.php");

?>

