<?php
// 受信したメッセージ情報
$accessToken = 'YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=';

$raw = file_get_contents('php://input');
$receive = json_decode($raw, true);

$event = $receive['events'][0];
$reply_token  = $event['replyToken'];

$headers = array('Content-Type: application/json',
                 'Authorization: Bearer ' . $accessToken);

//ユーザーからのメッセージ取得
$json_string = file_get_contents('php://input');
$json_object = json_decode($json_string);

//取得データ
$replyToken = $json_object->{"events"}[0]->{"replyToken"};        //返信用トークン
$message_type = $json_object->{"events"}[0]->{"message"}->{"type"};    //メッセージタイプ
$message_text = $json_object->{"events"}[0]->{"message"}->{"text"};    //メッセージ内容//テキスト応答

if($message_text != "感動する映画") {
    //カルーセル応答
    $columns = array(
                     array('thumbnailImageUrl' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSQR4kVP0EtyH3o8WqrXed5tPc8KY3kRL7Tj55MDPPIkgw3EoQl7t06EER6VA',
                           'title'   => '映画',
                           'text'    => '感動する',
                           'actions' => array(array('type' => 'message', 'label' => 'ラベルです', 'text' => '感動する映画'))
                     ),
                     array('thumbnailImageUrl' => 'https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSQR4kVP0EtyH3o8WqrXed5tPc8KY3kRL7Tj55MDPPIkgw3EoQl7t06EER6VA',
                           'title'   => '漫画',
                           'text'    => '感動する',
                           'actions' => array(array('type' => 'message', 'label' => 'ラベルです', 'text' => '感動する映画'))
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

} else {

    //返信メッセージ
    $return_message_text = "「" . $message_type.$message_text . "」じゃねーよｗｗｗ";

    //返信実行
    sending_messages($accessToken, $replyToken, $message_type, $return_message_text);
   }
    ?>
    <?php
    //メッセージの送信
    function sending_messages($accessToken, $replyToken, $message_type, $return_message_text){
        //レスポンスフォーマット
        $response_format_text = [
            "type" => $message_type,


            "text" => $return_message_text
        ];

        //ポストデータ
        $post_data = [
            "replyToken" => $replyToken,
            "messages" => [$response_format_text]
        ];

        //curl実行
        $ch = curl_init("https://api.line.me/v2/bot/message/reply");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charser=UTF-8',
            'Authorization: Bearer ' . $accessToken
        ));
        $result = curl_exec($ch);
        curl_close($ch);
      }

?>
