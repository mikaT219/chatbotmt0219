<?php
// 受信したメッセージ情報
$accessToken = 'YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=';

$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);

$event = $receive['events'][0];
$reply_token  = $event['replyToken'];

$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $accessToken);

//テキスト応答
// $message = array('type' => 'text',
//                 'text' => 'こんにちは。テキスト応答ですよ。');
$message = array('type'      => 'sticker',
                 'packageId' => 1,
                 'stickerId' => 1);

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
