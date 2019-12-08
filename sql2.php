<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/vendor/linecorp/line-bot-sdk/src/LINEBot/Event/MessageEvent/TextMessage.php';

$pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net;charset=utf8","bef176e47e8f17","d24f08d0");
// $pdo = new PDO('mysql:dbname=heroku_95ce0246cf019ce;host=us-cdbr-iron-east-05.cleardb.net;port=3306;charset=utf8','b200b128c40131','f1376c03');

//prepareメソッドでSQLをセット
$stmt = $pdo->prepare("select title from recmmend_table where id = 2");

//executeでクエリを実行
$stmt->execute();

// 結果をセット
$result = $stmt->fetch();

// echo $result;

// echo "\n";

echo "title = ".$result['title'].PHP_EOL;


?>
