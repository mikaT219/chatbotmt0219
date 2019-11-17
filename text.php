<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/index.php';



//配列に格納された各イベントをループで処理
foreach ($events as $event) {
 // MessageEventクラスのインスタンスでなければ処理をスキップ
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent)) {
        error_log('Non message event has come');
        continue;

 // TextMessageBuilderクラスのインスタンスでなければ処理をスキップ
    if (!($event instanceof \LINE\LINEBot\Event\MessageEvent\TextMessage)) {
        error_log('Non text message has come');
        continue;

  //テキストを返信し次のイベントへ
      //replyTextMessage($bot, $event->getReplyToken(), $event->getText().'なの？');
     replyTextMessage($bot, $event->getReplyToken(), $event->$res.'なの？');
    }


    //テキストを返信。引数はLINEBot、返信先、テキスト
      // 返信を行いメッセージを取得
      // TextMessageBuilderの引数はテキスト
      $response = $bot->replyMessage($replyToken, new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($text));

      //レスポンスが異常な場合
      if(!$response->isSucceeded()){
        //エラー内容を出力
        error_log('Failed! '. $response->getHTTPStatus . ' '.$response->getRawBody());
      }
    }

  }
?>
