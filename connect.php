<?php

$link = mysqli_connect('us-cdbr-iron-east-05.cleardb.net', 'bef176e47e8f17', 'd24f08d0', 'heroku_1ac9c94b4480f8f');

// 接続状況をチェックします
if (mysqli_connect_errno()) {
    die("データベースに接続できません:" . mysqli_connect_error() . "\n");
} else {
    echo "データベースの接続に成功しました。\n";
}
