<?php
//電車遅延情報の元となるrss
define("rssURL","http://xxx");

//AppStoreMyURLはhttps://itunes.apple.com/jp/app/割り当てられるid
//例. https://itunes.apple.com/jp/app/id284882215"
define("AppStoreMyURL","https://itunes.apple.com/jp/app/id965111665");

//////////アプリアップデート(ダイアログ表示)//////////
//iTunesConnectにてアプリ申請が通ったら、xcodeと同じversionを入力。
$new_AppVersion = '1.3';
//$UpDateDetail = '最新バージョンではアプリを開いた時や開き直した際、常に自動更新し最新情報を取得します。';
//$UpDateDetail = '最新バージョンではプッシュ通知が出来るようになりました。\nお好きな沿線を選んで設定しておくと、その沿線で遅延が発生した際にプッシュ通知されとても便利です。';
$UpDateDetail = '';

$info_app_update  = array(
'new_AppVersion' => $new_AppVersion,
'title' => 'アプリのアップデートについて',
'message' => '\n新しいバージョンのアプリがリリースされています。\n\n今ご使用のバージョン：my_AppVersion\n最新バージョン：'.$new_AppVersion.'\n\n'.$UpDateDetail.'\n\nアップデートをオススメします。\nApp Storeでアップデートを行いますか？',
);

//////////プッシュ通知関連//////////
$push_notice_mode ='test';
// $push_notice_mode ='honban'
$push_notice_URL  = array(
'registDeviceTokenURL' => 'http://playpoint.flymark.net/push_notice/ios/'.$push_notice_mode.'/from_device/regist_device_token.php',
);

?>