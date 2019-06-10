<?php 

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('　「　記事一覧　」　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


// 画面表示用データ取得
//================================
// GETパラメータを取得
//----------------------------------
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? (int)$_GET['p'] : 1; //デフォルトは１ページ目
//カテゴリー
debug('デバック■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■'.print_r(gettype($currentPageNum),true));
//$category = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
//ソート順
$sort = (!empty($_GET['sort'])) ? $_GET['sort'] : '';
//パラメータに不正な値が入っているかチェック
if(!is_int($currentPageNum)){
    error_log('エラー発生：指定ページに不正な値が入りました');
    debug('デバック■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■'.print_r($currentPageNum,true));
    header("Location:index.php"); //トップページへ
}else{
    debug('デバック■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■'.print_r($currentPageNum,true));
    
}
//表示件数
$listSpan = 10;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //１ページ目なら(1-1)*20=0,2ページ目なら(２−１)*20=20
//DBから商品データを取得
$dbProductData = getDataList($currentMinNum,  $sort);
//DBからカテゴリデータを取得
//$dbCategoryData = getCategory();
//debug('DBデータ：'.print_r($dbFormData,true));
//debug('カテゴリデータ：'.print_r($dbCategoryData,true));



debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
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
        <h2 class="icon">記事一覧</h2>
        <h3><a href="index.php">&lt;&lt;TOPへ</a></h3>
    </div>
    <div class="panel-list">
    <?php 
        foreach($dbProductData['data'] as $key => $val):
    ?>
        <?php echo '<div class="panel-body '.$val['data_id'].' ">';        ?>
            <div class="icon">
                <?php 
                    tweet();
                ?>
            </div>
            <div class="date">
                <?php echo sanitize($val['date']); ?>
            </div>
            <div class="today">
                today： <?php echo sanitize($val['today']); ?> h
            </div>
            <div class="total">
                total :   <?php echo sanitize($val['total']); ?> h
            </div>
            <div class="contents">
                <?php echo sanitize($val['contents']); ?>
            </div>
        </div>
    <?php 
        endforeach;
    ?>
    </div>
    
    <?php 
    pagination($currentPageNum, $dbProductData['total_page'],); 
    
    ?>


</section>
   
   <?php 
    tweet();
    ?>
    
    </body>
</html>