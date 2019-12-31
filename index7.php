<?php
// 受信したメッセージ情報
$accessToken = 'YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=';

$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);

$event = $receive['events'][0];
$reply_token  = $event['replyToken'];

$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $accessToken);

//カルーセル応答
$columns = array(
                 array('thumbnailImageUrl' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSQR4kVP0EtyH3o8WqrXed5tPc8KY3kRL7Tj55MDPPIkgw3EoQl7t06EER6VA',
                       'title'   => '映画',
                       'text'    => '感動する',
                       'actions' => array(array('type' => 'message', 'label' => 'ラベルです', 'text' => '感動する'))
                 ),
                 array('thumbnailImageUrl' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSQR4kVP0EtyH3o8WqrXed5tPc8KY3kRL7Tj55MDPPIkgw3EoQl7t06EER6VA',
                       'title'   => '漫画',
                       'text'    => '感動する',
                       'actions' => array(array('type' => 'message', 'label' => 'ラベルです', 'text' => '感動する'))
                 )
            );

$template = array('type'    => 'carousel',
                  'columns' => $columns,
                );

$message = array('type'     => 'template',
                 'altText'  => '代替テキスト',
                 'template' => $template
                );


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
