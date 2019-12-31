<?php
// 受信したメッセージ情報
$accessToken = 'YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=';

$jsonString = file_get_contents('php://input'); error_log($jsonString);
$jsonObj = json_decode($jsonString); $message = $jsonObj->{"events"}[0]->{"message"};
$replyToken = $jsonObj->{"events"}[0]->{"replyToken"};


 // 送られてきたメッセージの中身からレスポンスのタイプを選択
if ($message->{"text"} == 'カルーセル') {
     // カルーセルタイプ
    $messageData = [
        'type' => 'template',
        'altText' => 'カルーセル',
        'template' => [
             'type' => 'carousel',
            'columns' => [
                [
                    'title' => '映画',
                    'text' => '映画',
                     'actions' => [
                         [
                            'type' => 'message',
                             'label' => '感動する映画',
                             'text' => '感動する映画'
                         ],
                         [
                           'type' => 'message',
                            'label' => '笑える映画',
                            'text' => '笑える映画'
                         ]
                    ]
                ],
                 [
                        'title' => '漫画',
                        'text' => '漫画',
                        'actions' => [
                            [
                              'type' => 'message',
                               'label' => '感動する漫画',
                               'text' => '感動する漫画'
                            ],
                            [
                              'type' => 'message',
                               'label' => '笑える漫画',
                               'text' => '笑える漫画'
                            ]
                        ]
                    ],
                    // [
                    //        'title' => '小説',
                    //        'text' => '小説',
                    //        'actions' => [
                    //            [
                    //                'type' => 'postback',
                    //                'label' => 'webhookにpost送信',
                    //                'data' => 'value'
                    //            ],
                    //            [
                    //                'type' => 'uri',
                    //                'label' => '女美会を見る',
                    //                'uri' => 'https://jobikai.com/'
                    //            ]
                    //        ]
                    //    ],
                ]
            ]
    ];
 } else {
     // それ以外は送られてきたテキストをオウム返し
     $messageData = [ 'type' => 'text', 'text' => $message->{"text"} ];
     //DB接続
       $pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");
       // $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = "."'".$message->{"text"}."'");
       // $messageData = [ 'type' => 'text', 'text' => "select title from recmmend_table where genre_feeling ="."'".$message->{"text"}."'" ];
       // $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = '感動する映画'");
       $stmt = [ 'type' => 'text', 'text' => "select title from recmmend_table where genre_feeling ="."'".$message->{"text"}."'" ];
       //executeでクエリを実行
       $stmt->execute();
       // 結果をセット
       $result = $stmt->fetch();
       // $messageData= $stmt->fetch();
       $messageData= $result['title'];
}

//messagedateの送信
$response = [ 'replyToken' => $replyToken, 'messages' => [$messageData] ];
error_log(json_encode($response));

//curl実行
$ch = curl_init('https://api.line.me/v2/bot/message/reply');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($response));
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json; charser=UTF-8', 'Authorization: Bearer ' . $accessToken ));
$result = curl_exec($ch); error_log($result);
curl_close($ch);
?>
