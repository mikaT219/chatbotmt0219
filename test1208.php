<?php

// DB接続
try {
    $pdo = new PDO ( "mysql:dbname=heroku_1ac9c94b4480f8f; host=us-cdbr-iron-east-05.cleardb.net; charset=utf8", "bef176e47e8f17", "d24f08d0");

    $DB_connection = '接続に成功しました。';
    print '接続に成功しました。';
} catch ( PDOException $e ) {
    $DB_connection = "接続エラー:{$e->getMessage()}";
    print "接続エラー:{$e->getMessage()}";
}

//prepareメソッドでSQLをセット
$stmt = $pdo->prepare("select title from recmmend_table where id = 2");

//executeでクエリを実行
$stmt->execute();

// 結果をセット
$result = $stmt->fetch();

//結果を格納
$line_mes =  "name = ".$result['title'].PHP_EOL;
//結果を表示
echo "name = ".$result['title'].PHP_EOL;

// Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// 署名の獲得
$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

// 署名が正当かチェック。正当であればリクエストをパースし配列へ
// 不正であれば例外の内容を出力
try {
    $events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);
}
catch(\LINE\LINEBot\Exception\InvalidSignatureException $e) {
    error_log("parseEventRequest failed. InvalidSignatureException => ".var_export($e, true));
}
catch(\LINE\LINEBot\Exception\UnknownEventTypeException $e) {
    error_log("parseEventRequest failed. UnknownEventTypeException => ".var_export($e, true));
}
catch(\LINE\LINEBot\Exception\UnknownMessageTypeException $e) {
    error_log("parseEventRequest failed. UnknownMessageTypeException => ".var_export($e, true));
}
catch(\LINE\LINEBot\Exception\InvalidEventRequestException $e) {
    error_log("parseEventRequest failed. InvalidEventRequestException => ".var_export($e, true));
}

// 配列に格納された各イベントをループで処理
foreach ($events as $event) {
// MessageEventクラスのインスタンスでなければ処理をスキップ
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
        error_log('Non message event has come');
        continue;
    }
// MessageEventクラスのインスタンスでなければ処理をスキップ
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
        error_log('Non text message has come');
        continue;
    }
//格納した返信をLINEに返す
    $bot->replyText($event->getReplyToken(), $line_mes);

}

?>
