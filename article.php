<?php 

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('　「　記事一覧　」　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

?>


<!--ヘッダー-->
<?php 
require('header.php');
?>

<!--headタグ-->
<?php
$siteTitle = '記事一覧';
require('head.php'); 
?>


<body>
 
<?php
//        DBへ接続
$dbh = dbConnect();

//        DBからデータを取得
//        1.テーブルにある全てのデータを取得するSQL文を、変数に格納
$sql = "SELECT * FROM data order by data_id desc";
//        2.SQL文を実行するコードを、変数に格納
$stmt = $dbh->query($sql);
//        3.foreach文でデータベースより取得したデータを１行ずるループ処理（連想配列で取得したデータのうち、１行文が$rowに格納

?>
<section class="past">
    <div class="top-icon">
        <h2 class="icon">記録</h2>
        <h3><a href="index.php">&lt;&lt;TOPへ</a></h3>
    </div>
    <?php 
    foreach($stmt as $row){
        //        4.連想配列形式の1行のデータから、キーを指定し、出力する
    ?>
    <div class="form">
        <?php 
        echo 
            '勉強した日  '.$row['date'].'<br>'
            .'today(h)  '.$row['today'].'<br>'
            .'total(h)  '.$row['total'].'<br>'
            .'内容  '.$row['contents'].'<br>'; 
        ?>
    </div>
    <?php
    }
    ?>


</section>

?>



    </body>
</html>