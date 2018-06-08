<?php 
// サインインしているユーザーの情報を取得し、返す関数
// 引数$dbh;データベース接続オブジェクト
// 引数$user_id;サインインしとるユーザーのid
// 使い方:get_singin_user($dbh,$_SESSION["id"])
// $user_id=$_SESSION["id"]

function get_user($dbh,$user_id){
	$sql='SELECT * FROM `users` WHERE `id`=?';
    $data=array($user_id);
    $stmt=$dbh->prepare($sql);
    $stmt->execute($data);
    
    $user=$stmt->fetch(PDO::FETCH_ASSOC);

    return $user;


}


// ログイン済みかチェックし、未ログインであればログイン画面に戻す
 function check_signin($user_id){
 	if (!isset($user_id)) {
 		header("Location:signin.php");
 		exit();
 	}
 }

?>