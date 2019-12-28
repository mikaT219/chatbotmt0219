<?php
// 受信したメッセージ情報
require_once __DIR__.'/index5.php';
$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);

$event = $receive['events'][0];
$reply_token  = $event['replyToken'];

$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $accessToken);

$message = array('type' => 'text',
                'text' => 'こんにちは。テキスト応答ですよ。');

$body = json_encode(array('replyToken' => $reply_token,
                          'messages'   => array($message)));
$options = array(CURLOPT_URL            => 'https://api.line.me/v2/bot/message/reply',
                 CURLOPT_CUSTOMREQUEST  => 'POST',
                 CURLOPT_RETURNTRANSFER => true,
                 CURLOPT_HTTPHEADER     => $headers,
                 CURLOPT_POSTFIELDS     => $body);

$curl = curl_init();
curl_setopt_array($curl, $options);
curl_exec($curl);
curl_close($curl);
?>