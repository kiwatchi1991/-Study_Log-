<?php 

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('　「　記事一覧　」　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


//        DBへ接続
$dbh = dbConnect();

//        DBからデータを取得
//        1.テーブルにある全てのデータを取得するSQL文を、変数に格納
$sql = "SELECT * FROM data order by data_id desc";
//        2.SQL文を実行するコードを、変数に格納
$stmt1 = $dbh->query($sql);
//        3.foreach文でデータベースより取得したデータを１行ずるループ処理（連想配列で取得したデータのうち、１行文が$rowに格納











?>