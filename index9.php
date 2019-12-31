<?php
// 受信したメッセージ情報
$accessToken = 'YDD9P7LxFOm4QRxH6k6vvNL4hiJdkZPZdXGr8biDB9RXWEX/RWCBl5JmIXQF/xexMMhDHHryHDiPPyUtiHgFPf7BtaIdK2bXUGTrITBa2loTalSN1zT3JxHBNtSmAQksGy/p4gRk8oHUwlQJXKvuPwdB04t89/1O/w1cDnyilFU=';
//DB接続
  $pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");
  // $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = "."'".$message->{"text"}."'");
  // $messageData = [ 'type' => 'text', 'text' => "select title from recmmend_table where genre_feeling ="."'".$message->{"text"}."'" ];
  $stmt = $pdo->prepare("select title from recmmend_table where genre_feeling = '感動する映画'");
  // $stmt = [ 'type' => 'text', 'text' => "select title from recmmend_table where genre_feeling ="."'".$message->{"text"}."'" ];
  //executeでクエリを実行
  $stmt->execute();
  // 結果をセット
  $result = $stmt->fetch();
  // $messageData= $stmt->fetch();
  $messageData= $result['title'];
  echo $messageData
?>
