<?php

/**
 * D10：簡易掲示板の作成（普通）
 * 同一ページ内で入力・表示を行う簡易掲示板を作成しなさい。JavaScriptは利用できない。
 * ● 入力された内容は「 log.json 」に保存
 * ● 入力内容は「名前」「内容」とする
 * ● 表示欄を設け「名前」「内容」「投稿日時」を表示する
 */

$log_dir = __DIR__ . '/data';
$log_filename = 'log.json';
$log_path = $log_dir . '/' . $log_filename;


//ログファイルが存在すれば読み込む
if (file_exists($log_path)) {
	$messages = json_decode(file_get_contents($log_path), true);
} else {
	$messages = array();
}
date_default_timezone_set("Asia/Tokyo");

//新規投稿があれば取り出して書き込む
if ( isset($_POST['message']) && isset($_POST['name']) ) {
	$new_name = $_POST['name'];
	$new_message = $_POST['message'];
	$messages[] = [
		"name" => $new_name,
		"message" => $new_message,
		"time" => time(),
	];
	$message_json = json_encode($messages);
	file_put_contents($log_path, $message_json);
}

//新しいメッセージが上に来るように並べ替え
usort($messages, function($a, $b){
	return $a["time"] > $b["time"] ? 1 : -1;
});
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>掲示板</title>
	<link rel="stylesheet" href="style.css">
</head>

<body>
	<h1>掲示板</h1>
	<form method="post" action="">
		<p>名前</p>
		<input type="text" name="name" required>
		<p>内容</p>
		<textarea name="message" required cols="30" rows="10"></textarea>
		<p>
		<input type="submit" value="投稿">
		</p>
	</form>
	<ul>
		<?php
		if (isset($messages)) {
			foreach ($messages as $key => $message) {
				//新規投稿だけに適用するクラスを指定
				$li_class = '';
				if ($key === 0) {
					$li_class = ' class="newest"';
				}
				echo "<li {$li_class}>
				名前 : " . htmlspecialchars($message["name"]) . '<br>
				メッセージ : ' . htmlspecialchars($message["message"]) . '<br>
				投稿日時 : ' . date("Y-m-d H:i:s", $message["time"]) . '<br>
				</li>';
			}
		}
		?>
	</ul>
</body>

</html>