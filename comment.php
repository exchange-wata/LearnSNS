<?php 
session_start();
require('dbconnect.php');

// $data=array($comment,$signin_user,$feed_id);のところで必要
$signin_user=$_SESSION["id"];
$comment=$_POST["write_comment"];
$feed_id=$_POST["feed_id"];

// 必要な値を取得
// コメントをinsertするSQL文作成
$sql='insert into comments
	  set comment=?,user_id=?,feed_id=?,created=now()';

// $sql='insert into comments(comment,user_id,feed_id,created)
// 	values(?,?,?,now());'

// SQL文実行
$data=array($comment,$signin_user,$feed_id);
$stmt=$dbh->prepare($sql);
$stmt->execute($data);


// feedsテーブルにcommentのカウントをupdateする
// SQL文作成
$update_sql='update `feeds` 
			 set `comment_count`=`comment_count`+1
			 where `id`=? ';
$update_data=array($feed_id);


// SQL文実行
$update_stmt=$dbh->prepare($update_sql);
$update_stmt->execute($update_data);


// timeline.php(一覧)に戻る
header("Location:timeline.php");

	
// echo "<pre>";
// var_dump($_POST);
// echo "</pre>";

?>