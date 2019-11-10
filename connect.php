<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

//host,user,pass,dbname
$link = mysqli_connect('us-cdbr-iron-east-05.cleardb.net', 'bef176e47e8f17', 'd24f08d0', 'heroku_1ac9c94b4480f8f');

// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
} else {
    echo "データベースの接続に成功しました。\n";
}

// userテーブルの全てのデータを取得する
$query = "SELECT DataColumn FROM cardinfo;";

// クエリを実行します。
if ($result = mysqli_query($link, $query)) {
    echo "SELECT に成功しました。\n";
    foreach ($result as $row) {
        var_dump($row);
    }
}

// 接続を閉じます
mysqli_close($link);
