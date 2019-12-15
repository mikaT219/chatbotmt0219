<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);
// LINE Messaging APIがリクエストに付与した署名を取得
$signature = $_SERVER['HTTP_' . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];
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

//DB接続
try {
  $pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");
    $DB_connection = '接続に成功しました。';
    print '接続に成功しました。';
} catch ( PDOException $e ) {
    $DB_connection = "接続エラー:{$e->getMessage()}";
    print "接続エラー:{$e->getMessage()}";
}

// 配列に格納された各イベントをループで処理
foreach ($events as $event) {
    //テキストを返信し次のイベントへ
    // $id = $event->getText();
    $id = '笑える映画';
    echo "id = ".$id;
    // }
    //クエリの格納
    // $stmt = $pdo->prepare("select title from recmmend_table where id = $id;");
    // $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = $id;");
    $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = "."'".$id."'".";");
    $stmt_test = "select title from recmmend_table where genre_feeling = $id;";
    //executeでクエリを実行
    $stmt->execute();
    // 結果をセット
    $result = $stmt->fetch();
    $line_mes =  "title = ".$result['title'].PHP_EOL;
    echo $line_mes;

    // 配列に格納された各イベントをループで処理
    // foreach ($events as $event) {
    //格納した返信をLINEに返す
    // $bot->replyText($event->getReplyToken(), $line_mes);
    // $bot->replyText($event->getReplyToken(), $event->getText());
    $bot->replyText($event->getReplyToken(), $line_mes);
}
?>
