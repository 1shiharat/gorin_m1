<?php
/**
 * D7：画像に透かしを入れる （簡単）
 * 提供された画像に透かし（ .png ファイル）を付けなさい。透かしの位置は右下隅とすること。
 */

 
// 元画像を取得
$sample = imagecreatefromjpeg(__DIR__ . '/sample.jpg');
// 元画像の幅を取得
$sample_width = imagesx($sample);
// 元画像の高さを取得
$sample_height = imagesy($sample);

// 透かしを取得
$watermark = imagecreatefrompng(__DIR__ . '/watermark.png');

// 透かしの幅を取得
$watermark_width = imagesx($watermark);
// 透かしの高さを取得
$watermark_height = imagesy($watermark);

// 重ねる時の位置を指定 : 右から10px
$position_right = 10;
// 重ねる時の位置を指定 : 下から10px
$position_bottom = 10;


$c = imagecopymerge( 
	$sample, // 元画像
	$watermark, // 透かし
	$sample_width - $watermark_width - $position_right,  // 元画像の重ねる時のx座標を指定
	$sample_height - $watermark_height - $position_bottom, // 元画像の重ねる時のy座標を指定
	0, // 透かしのx座標位置指定
	0, // 透かしのy座標位置指定
	$watermark_width, // 透かしの幅
	$watermark_height, // 透かし高さ
	100 // 透明度を指定
);

// ヘッダー出力
header("Content-Type: image/jpg");

// jpegとして出力
imagejpeg($sample);

// メモリを開放
imagedestroy($sample);
