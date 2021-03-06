<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/vendor/linecorp/line-bot-sdk/src/LINEBot/Event/MessageEvent/TextMessage.php';
require_once __DIR__.'/vendor/linecorp/line-bot-sdk/src/LINEBot/Constant/HTTPHeader.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名が正当かチェック。正当であればリクエストをパースし配列へ
$events = $bot->parseEventRequest(file_get_contents('php://input'),$signature);

//DB接続
$pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");

//ジャンルの格納
foreach ($events as $event) {
  $id = $event->getText();
}
  //クエリの格納
  $stmt = $pdo->prepare("select title from recmmend_table where id = $id");
  //executeでクエリを実行
  $stmt->execute();
  // 結果をセット
  $result = $stmt->fetch();
  echo "title = ".$result['title'].PHP_EOL;
