<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/connect.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

// LINE Messaging APIがリクエストに付与した署名を取得
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

  //配列に格納された各イベントをループで処理
  foreach ($events as $event) {
  //テキストを返信し次のイベントへ
  //replyTextMessage($bot, $event->getReplyToken(), $res);
  //画像を返信
  //replyImageMessage($bot, $event->getReplyToken(),'https://'.
                      //  $_SERVER['HTTP_HOST'].
                      //  '/imgs/download1.jpg',
                      // 'https://'.$_SERVER['HTTP_HOST'].
                      //'/imgs/download2.jpg');

  //カルーセルテンプレートメッセージを返信
  //ダイアログの配列
      $columnArray = array();
      for ($i =0; $i<1; $i++) {
      ////アクションの配列
      $actionArray = array();
      array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
        '映画','映画'));
        //'ボタン' .$i . '-' . 1, 'c-' .$i .'-' . 1));
      array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
        '小説','小説'));
        //'ボタン' .$i . '-' . 2, 'c-' .$i .'-' . 2));
      array_push ($actionArray, new LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder(
        //'ボタン' .$i . '-' . 3, 'c-' .$i .'-' . 3));
        '漫画','漫画'));

      $column = new LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder (
        ($i + 1).'セレクト',
        'ジャンル',
        'https://'.$_SERVER['HTTP_HOST'].'/imgs/template.jpg',
        $actionArray
      );
      ////追加
      array_push($columnArray, $column);
      }
      replyCarouselTemplate($bot, $event->getReplyToken(),'ジャンル',$columnArray);
    }

    //テキストを返信。引数はLINEBot、返信先、テキスト
    function replyTextMessage($bot,$replyToken,$text) {
      // 返信を行いメッセージを取得
      // TextMessageBuilderの引数はテキスト
      $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

      //レスポンスが異常な場合
      if(!$response->isSucceeded()){
        //エラー内容を出力
        error_log('Failed! '. $response->getHTTPStatus . ' '.$response->getRawBody());
      }
    }

    //画像を返信。引数はLINEBot、返信先、画像URL、サムネイルURL
    function replyImageMessage($bot,$replyToken,$originalImageUrl,$previewImageUrl){
      // ImageMessageBuilderの引数は画像URL、サムネイルURL
      $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($originalImageUrl, $previewImageUrl));
      if(!$response->isSucceeded()){
        error_log('Failed! '. $response->getHTTPStatus . ' '.$response->getRawBody());
      }
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
