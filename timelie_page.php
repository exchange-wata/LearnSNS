<?php
	require('dbconnect.php');
	
	// 1ページあたりのページ数
	const CONTENT_PER_PAGE = 5;

	// -1などの不正な値を渡された時の対策
	$page = max($page,1);
	// スキップするレコード数を指定する
	// スキップするレコード数 = (指定ページ　- 1) * 表示件数
	$start = ($page -1) * CONTENT_PER_PAGE;

	// feedsテーブルのレコードを取得
	// COUNT() レコードの数を集計するSQL
	$sql_cnt = 'SELECT COUNT(*) AS `cnt` FROM `feeds`';
	$stmt_cnt = $dbh->prepare($sql_cnt);
	$stmt_cnt->execute();
	$result_cnt = $stmt_cnt->fetch(PDO::FETCH_ASSOC);


	// 1ページあたりに表示する投稿件数を指定する	
	$sql = 'SELECT `f`.*, `u`.`name`, `u`.`img_name` 
                  FROM `feeds` AS `f` 
                  LEFT JOIN `users` AS `u` 
                  ON `f`.`user_id` = `u`.`id` 
                  ORDER BY `f`.`created` DESC LIMIT ' . CONTENT_PER_PAGE .' OFFSET ' . $start;
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$cnt = $result['cnt'];


	// 最後のページ数を取得
	// 最後のページ　＝取得したページ/1ページあたりのページ数
	$last_page = ceil($result_cnt['cnt']/CONTENT_PER_PAGE);

	// 最後のページより大きい値を渡された際の対策
	$page = min($page, $last_page);
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