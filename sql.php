<?php
//Composerでインストールしたライブラリを一括読み込み
require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/vendor/linecorp/line-bot-sdk/src/LINEBot/Event/MessageEvent/TextMessage.php';

// アクセストークンを使いCurlHTTPClientをインスタンス化
//$httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('CHANNEL_ACCESS_TOKEN'));
// CurlHTTPClientとシークレットを使いLINEBotをインスタンス化
//$bot = new \LINE\LINEBot($httpClient, ['channelSecret' => getenv('CHANNEL_SECRET')]);

// LINE Messaging APIがリクエストに付与した署名を取得
//$signature = $_SERVER["HTTP_" . \LINE\LINEBot\Constant\HTTPHeader::LINE_SIGNATURE];

//PDOオブジェクトの生成
//$pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net",bef176e47e8f17,d24f08d0);
$pdo = new PDO("mysql:dbname=heroku_1ac9c94b4480f8f;host=us-cdbr-iron-east-05.cleardb.net","bef176e47e8f17","d24f08d0");
//prepareメソッドでSQLをセット
$stmt = $pdo->prepare("select title from recmmend_table where feeling =?");


//bindValueメソッドでパラメータをセット
$stmt->bindValue(1,'be impress');

//executeでクエリを実行
$stmt->execute();

//PDO Statementオブジェクトをそのままforeachへ
foreach($stmt as $loop){
    //結果を表示
    echo $loop['title'].PHP_EOL;
}
//host,user,pass,dbname
//$link = mysqli_connect('us-cdbr-iron-east-05.cleardb.net', 'bef176e47e8f17', 'd24f08d0', 'heroku_1ac9c94b4480f8f');

//$events = $bot->parseEventRequest(file_get_contents('php://input'), $signature);

// 接続状況をチェックします
// if (mysqli_connect_errno()) {
//     die("データベースに接続できません:" . mysqli_connect_error() . "\n");
// } else {
//     echo "データベースの接続に成功しました。"."\n";


//$query = "SELECT title,details FROM recmmend_table where id = 4;";
//$query = "SELECT title,details FROM recmmend_table where feeling LIKE 'relaxed';";
// $query = "SELECT title,details FROM recmmend_table where feeling = 'be impress';";
//$query = "SELECT title,details FROM recmmend_table WHERE feeling LIKE 'be impress';";
//$query = "SELECT title,details FROM recmmend_table WHERE feeling LIKE 'be impress' ORDER BY rand() LIMIT 3;";
//echo $query;

// クエリを実行します。
// if ($result = mysqli_query($link, $query)) {
//     //echo "SELECT に成功しました。\n";
//     foreach ($result as $row) {
//         //$str = mb_convert_encoding($row,"utf-8","sjis");
//         //$res = print_r($str,true);
//         $res = $row["title"].",".$row["details"]."\n";
//         //$res = $row["title"]."\n";
//         echo $res;
//         //echo $row["title"];

    // }
// }
?>
