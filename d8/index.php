<?php

/**
 * D8：フォームバリデーション（普通）
 * 入力フォームに対してバリデーション機能を付けなさい。 バリデーションは送信ボタンをクリックしたらクライアントで行うこと。
 * パラメータ 制限
 * Name 入力必須
 * Mail アドレス形式の確認（＠ が含まれているかのみでよい）
 * Number 1-10 の整数
 */

$vars = [
	"name" => [
		"value" => "",
		"error" => "",
	],
	"email" => [
		"value" => "",
		"error" => "",
	],
	"number" => [
		"value" => "",
		"error" => "",
	],
];
$vars["name"]["value"] = filter_input(INPUT_POST, "name");
$vars["email"]["value"] = filter_input(INPUT_POST, "email");
$vars["number"]["value"] = filter_input(INPUT_POST, "number");

if ($_POST) {
	foreach ($vars as $key => $setting) {
		switch ($key) {
			case "name":
				if (!$setting["value"]) {
					$vars[$key]["error"] = "入力必須です";
				}
				break;
			case "email":
				if ($setting["value"] && !filter_var($setting["value"], FILTER_VALIDATE_EMAIL)) {
					$vars[$key]["error"] = "メールアドレス形式で入力が必要です";
				}
				break;
			case "number":
				if ($setting["value"] && !filter_var($setting["value"], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 10]])) {
					$vars[$key]["error"] = "1-10までの整数で入力してください";
				}
				break;
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>フォームバリデーション</title>
</head>

<body>
	<form action="./" method="POST">
		<?php

		foreach ($vars as $name => $setting) {
		?>
			<p><?php echo strtoupper($name) ?></p>
			<input type="text" name="<?php echo $name ?>" value="<?php echo $setting["value"] ?>" />
			<?php
			if ($setting["error"]) {
			?>
				<p style="color: #F00"><?php echo $setting["error"] ?></p>
		<?php
			}
		}
		?>
		<p>
			<button type="submit">送信する</button>
		</p>
	</form>
</body>

</html>