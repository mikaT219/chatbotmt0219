<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/connect2.php';
// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
// 署名が正当かチェック。正当であればリクエストをパースし配列へ
$events = $bot->parseEventRequest(file_get_contents('php://input'),$signature);
//配列に格納された各イベントをループで処理
foreach ($events as $event) {
  //テキストを返信し次のイベントへ
  // replyTextMessage($bot, $event->getReplyToken(), 'TextMessage');
  replyTextMessage($bot, $event->getReplyToken(), $event->getText());
  // replyTextMessage($bot, $event->getReplyToken(), $result);
}

// //テキストを返信。引数はLINEBot、返信先、テキスト
function replyTextMessage($bot,$replyToken,$text) {
  // 返信を行いメッセージを取得
  // TextMessageBuilderの引数はテキスト
  $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

}
?>
