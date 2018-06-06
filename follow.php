<?php
// session変数を使えるようにする→今ログインしとるユーザー
	session_start(); 


// DB接続
	require('dbconnect.php');


// feed_idを取得 <-GET送信で送られてきとる前提
	$user_id=$_GET["user_id"];

	// var_dump($_GET);
	// var_dump($_SESSION);

// データを増やす=>insert
// SQL文作成
	$sql='insert into followers
		  set user_id=?,follower_id=?';

// SQL文実行
	$data=array($_GET["user_id"],$_SESSION["id"]);
	$stmt=$dbh->prepare($sql);
    $stmt->execute($data);


// 一覧に戻る
	header("Location:profile.php?user_id=".$user_id);

?>