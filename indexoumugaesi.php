<?php

// DB接続
try {
//    $pdo = new PDO ( 'mysql:dbname=heroku_95ce0246cf019ce; host=us-cdbr-iron-east-05.cleardb.net; port=3306; charset=utf8', 'b200b128c40131', 'f1376c03' );
    $pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");
    $DB_connection = '接続に成功しました。';
    print '接続に成功しました。';
} catch ( PDOException $e ) {
    $DB_connection = "接続エラー:{$e->getMessage()}";
    print "接続エラー:{$e->getMessage()}";
}

$stmt = $pdo->prepare("select title from recmmend_table where id = 2");
//executeでクエリを実行
$stmt->execute();
// 結果をセット
$result = $stmt->fetch();
// echo "title = ".$result['title'].PHP_EOL;


require_once __DIR__.'/vendor/autoload.php';
// require_once __DIR__.'/connect.php';

$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

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


foreach ($events as $event) {
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
        error_log('Non message event has come');
        continue;
    }

    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
        error_log('Non text message has come');
        continue;
    }

    $id = $event->getText();

    // $bot->replyText($event->getReplyToken(), $event->getText());
    $bot->replyText($event->getReplyToken(), $result);
    // $bot->replyText($event->getReplyToken(), $res);
    // $query = "SELECT title,details FROM recmmend_table where id = $id;";
    //prepareメソッドでSQLをセット
    // $stmt = $pdo->prepare("select title from recmmend_table where id = $id");
    // //executeでクエリを実行
    // $stmt->execute();
    // // 結果をセット
    // $result = $stmt->fetch();
}

?>
