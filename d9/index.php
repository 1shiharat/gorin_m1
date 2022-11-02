<?php
/**
 * D9：ファイルマネージャー（普通） 
 * ファイルやフォルダの追加、ファイルやフォルダの削除、ファイルの編集や保存を行うことがで
 * きるファイルマネージャーを作成しなさい。
 */

define("STORAGE_DIR", __DIR__ . "/files");

/**
 * ファイルを取得
 *
 * @param string $file
 * @param boolean $check_security
 * @return string
 */
function get_file_path($file, $check_security = true)
{
	$path = STORAGE_DIR . "/" . $file;
	if ($check_security) {
		$path = realpath($path);
		if (strpos($path, STORAGE_DIR) === false) die('不正な操作');
	}
	return $path;
}

/**
 * ファイルのURLを取得(相対パス)
 *
 * @param string $file
 * @return string
 */
function file_url($file)
{
	return str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
}


/**
 * ファイルアップロード時の処理
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// 1. 削除処理
	// filename というキーが$_POSTにあったら
	if (array_key_exists('filename', $_POST)) {

		// アップロード先のファイルパスを取得し
		$filename = get_file_path($_POST['filename']);
		// 削除する
		if (file_exists($filename)) unlink($filename);
	} else {
		// 2. アップロード処理
		// アップロードされたファイルを取得し
		$tmp = $_FILES['new_file']['tmp_name'];

		// アップロードに成功していたら
		if (is_uploaded_file($tmp)) {
			// ファイルパスを取得しmove_uploaded_fileで移動する
			$filename = get_file_path(basename($_FILES['new_file']['name']), false);
			move_uploaded_file($tmp, $filename);
		}
	}
	header("Location: $_SERVER[REQUEST_URI]");
	exit;
}

// ファイルの取得
$files = glob(get_file_path('*', false));
?>

<div id="file-manager">
	<h2>ファイルアップロード</h2>
	<ul id="file-list">
		<li>
			<form method="POST" enctype="multipart/form-data" id="new-file">
				<input type="file" name="new_file">
				<input type="submit" value="アップロード">
			</form>
		</li>
		<?php foreach ($files as $file) { ?>
			<li>
				<a href="<?php echo file_url($file) ?>">
					<?php echo basename($file) ?>
				</a>
				<form method="POST" id="delete-file">
					<input type="hidden" name="filename" value="<?php echo basename($file) ?>">
					<input type="submit" value="削除">
				</form>
			</li>
		<?php } ?>
	</ul>
</div>