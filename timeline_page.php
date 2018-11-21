<?php
	require('dbconnect.php');
	
	// 1ページあたりのページ数
	const CONTENT_PER_PAGE = 5;

	if (isset($_GET['page'])) {
	 	$page = $_GET['page'];
	}else{
		$page = 1;
	}

	// -1などの不正な値を渡された時の対策
	$page = max($page,1);

	// feedsテーブルのレコードを取得
	// COUNT() レコードの数を集計するSQL
	$sql = 'SELECT COUNT(*) AS `cnt` FROM `feeds`';
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$cnt = $result['cnt'];


	// 最後のページ数を取得
	// 最後のページ　＝取得したページ/1ページあたりのページ数
	$last_page = ceil($cnt/CONTENT_PER_PAGE);

	echo '<pre>';
	var_dump($cnt);
	echo '</pre>';



?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>paging</title>
</head>
<body>
	<p><?php echo $cnt; ?></p>
	<p><?php echo $last_page; ?></p>
	
</body>
</html>