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
      $columnArray = array();
      for ($i =0; $i<1; $i++) {
          //アクションの配列
          $actionArray = array();
          array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
            '小説','小説'));
          array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
            '漫画','漫画'));
          array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
            '映画','映画'));
         $column = new LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder (
           ($i + 1).'セレクト',
           'ジャンル',
           'https://'.$_SERVER['HTTP_HOST'].'/imgs/template.jpg',
           $actionArray
         );
          //追加
         array_push($columnArray, $column);
     }
     replyCarouselTemplate($bot, $event->getReplyToken(),'ジャンル',$columnArray);
  }

  //Carouselテンプレートを返信。引数はLINEBot、返信先、メッセージ(可変長引数)
  //ダイアログの配列
  function replyCarouselTemplate($bot, $replyToken, $alternativeText, $columnArray) {
    $builder = new \LINE\LINEBot\MessageBuilder\TemplateMessageBuilder(
      $alternativeText,
      // Carouselテンプレートの引数はダイアログの配列
      new \LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder(
        $columnArray)
    );
    $response = $bot->replyMessage($replyToken, $builder);
    if(!$response->isSucceeded()){
      error_log('Failed! '. $response->getHTTPStatus . ' '.$response->getRawBody());
    }
  }
?>
