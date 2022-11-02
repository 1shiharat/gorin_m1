<?php 
/**
 * D11：検索フォーム作成（普通）
 * 指定されたDBに接続し、検索フォームを作成しなさい。
 * ● 検索するための入力フォームを1つ作成すること
 * ● 検索された内容は、同ページ内に表示すること
 * ● DBに格納されている情報は、検索時に該当するものをすべて表示すること
 * 
 */

// ▼ サンプルDB作成用 SQL
// 
// DROP TABLE IF EXISTS `posts`;
// CREATE TABLE `posts` (
//   `id` int NOT NULL AUTO_INCREMENT,
//   `title` longtext,
//   `content` longtext,
//   PRIMARY KEY (`id`)
// ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

// INSERT INTO `posts` (`id`, `title`, `content`) VALUES
// ('1', 'テストタイトル1', 'テストコンテンツ1の文章です。鳥。'),
// ('2', 'テストタイトル2', 'テストコンテンツ2の文章です。魚。');

// 検索キーワードを取得
$search_keyword = filter_input(INPUT_GET, "search_keyword");

// 結果保存用配列
$results = [];

if ( $search_keyword ){
	$mysqli = new mysqli('127.0.0.1', 'root', 'xxxxxxxxxxx', 'testm1');
	if ($mysqli->connect_error) {
		echo $mysqli->connect_error;
		exit();
	} else {
		$mysqli->set_charset("utf8");
	}
	// SQL文
	// LIKEであいまい検索にする
	$sql = "SELECT * FROM posts WHERE title LIKE '%". htmlspecialchars($search_keyword) ."%' OR content LIKE '%". htmlspecialchars($search_keyword) ."%'";
	
	if ($result = $mysqli->query($sql)) {
		// 連想配列を取得
		while ($row = $result->fetch_assoc()) {
			$results[] = $row;
		}
		// 結果セットを閉じる
		$result->close();
	}

	$mysqli->close();
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>検索フォーム</title>
</head>
<body>
	<form action="./" method="GET">
		<input type="search" name="search_keyword" value="<?php echo $search_keyword ?>" />
		<button type="submit">検索</button>
	</form>
	<?php 
	if ( $search_keyword && ! $results ){
		echo "検索結果が見つかりませんでした";
	}
	if ( $results ){
		foreach( $results as $result ){
			?>
			<p>タイトル: <?php echo $result["title"] ?></p>
			<p>本文: <?php echo $result["content"] ?></p>
			<?php 
		}
	}
	?>
</body>
</html>
