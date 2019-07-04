<?php 

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('　「　トップページ　」　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

// 画面表示用データ取得
//================================
// GETデータを格納
$d_id =(!empty($_GET['d_id'])) ? $_GET['d_id'] : '';
debug('データID：'.print_r($d_id,true));

//DBから商品データを取得
$dbFormData = (!empty($d_id)) ? getData($d_id) : '';
//新規登録か編集画面か判別用フラグ
$edit_flg = (empty($dbFormData)) ? false : true;


//post送信されていた場合
if(!empty($_POST)){
    debug('POST送信があります。');

    //変数に代入　本来はサニタイズ必要
    $date = htmlspecialchars($_POST['date'],ENT_QUOTES);
    $today = htmlspecialchars($_POST['today'],ENT_QUOTES);
    $total = htmlspecialchars($_POST['total'],ENT_QUOTES);
    $contents = htmlspecialchars($_POST['contents'],ENT_QUOTES);

//フォームが入力されていない場合

    //勉強した日・today・total の形式チェック
//    validNumber($date,'date'); //カレンダーから入力で、必要なし
    validNumber2($today,'today');
    validNumber2($total,'total');
    
    
    //    未入力チェック
    validRequired($date, 'date');
    validRequired($today, 'today');
    validRequired($total, 'total');
    validRequired($contents, 'contents');
    
    if(empty($err_msg)){
      debug('バリデーションOKです。');
      try {
//        DBへ接続
    $dbh = dbConnect();
    
//    SQL文(クエリー作成)
//      編集画面の場面は、UPDATE文、新規登録画面の場合はINSERT文を生成
    if($edit_flg){
      debug('DB更新です。');
      $sql = 'UPDATE data SET date = :date, today = :today, total = :total, contents = :contents WHERE data_id = :d_id';
      $data = array(':date' => $date, ':today' => $today, ':total' => $total, ':contents' => $contents, ':d_id' => $d_id) ;
    }else{
      debug('DB新規登録です。');
      $sql = 'INSERT INTO data (date,today,total,contents) VALUES (:date,:today,:total,:contents) ';
      //プレースホルダーに値をセットし、SQL文を実行（サーバーにデータを保存）
      $data = array(':date' => $date,':today'=> $today,':total'=> $total, ':contents'=> $contents);
    }
    
    debug('SQL:'.$sql);
    debug('流し込みデータ：'.print_r($data,true));
      //クエリ実行
    $stmt = queryPost($dbh,$sql,$data);
    
      
      //クエリ成功の場合
      if($stmt){
//        $_SESSION['msg_success'] =SUC01; 
        debug('記事ページへ遷移します。');
        header("Location:article.php");
      }
            } catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG06;
      }
    }

  }
        

?>

<!--ヘッダー-->
<?php 
require('header.php');
?>

<!--headタグ-->
<?php
$siteTitle = 'TOP';
require('head.php');
?>

<body>
   
    

  

<!--   メインコンテンツ-->
   <div id="contents" class="site-width">
<!--       投稿-->
    <section class="today">
      <h2 class="icon">投稿</h2>
        <div class="form">
          <form action="" method="post">
            <dl>
              <!--date-->
              <div id="err_msg">
                <span class="err_msg"><?php if(!empty($err_msg['date'])) echo $err_msg['date']; ?></span>
              </div>
              <dt><span class="">STUDY DAY</span></dt>
              
              <dd>
              <input type="date" name="date" class="date" value="<?php echo getFormData('date'); ?>">
              </dd>
                       
              <!--today-->
               <div id="err_msg">
               <span class="err_msg"><?php if(!empty($err_msg['today'])) echo $err_msg['today']; ?></span>
               </div>
               <dt><span class="">TODAY(h)</span></dt>
                       
              <dd><input type="text" name="today" class="day" value="<?php echo getFormData('today'); ?>"></dd>
                       
              <!-- total-->
              <div id="err_msg">
              <span class="err_msg"><?php if(!empty($err_msg['total'])) echo $err_msg['total']; ?></span>
              </div>
              <dt><span class="">TOTAL(h)</span></dt>
              <dd><input type="text" name="total" class="total"  value="<?php echo getFormData('total'); ?>"></dd>
                       
              <!-- 内容-->
              <div id="err_msg">
              <span class="err_msg"><?php if(!empty($err_msg['contents'])) echo $err_msg['contents']; ?></span>
              </div>
              
              <dt><span class="">COMMENT</span></dt>
                       
              <dd><textarea name="contents"  cols="50" rows="10"  value=""><?php echo getFormData('contents'); ?></textarea></dd>
                      
            </dl>
              <button type="submit" class="btn">SUBMIT</button>
                   
              </form>
        </div>
     </section>
       
</div>
    
<?php 
    

    //        DBからデータを取得
    //DBへの接続準備
    $dbh = dbConnect();
    //        DBからデータを取得
    //        1.テーブルにある全てのデータを取得するSQL文を、変数に格納
    $sql = "SELECT * FROM data WHERE delete_flg = 0 ORDER by data_id desc";
    //        2.SQL文を実行するコードを、変数に格納
    $data = array();
    $stmt = queryPost($dbh, $sql, $data);
    //        3.foreach文でデータベースより取得したデータを１行ずるループ処理（連想配列で取得したデータのうち、１行文が$rowに格納
    
    ?>
    <section class="past">
       <div class="top-icon">
            <h2 class="icon">記録</h2>
           <h3><a href="article.php">&gt;&gt;記事一覧へ</a></h3>
        </div>
        <?php 
    foreach($stmt as $row){
        //        4.連想配列形式の1行のデータから、キーを指定し、出力する
        ?>
       <div class="form">
        <?php 
            echo 
             $row['date'].'<br>'
            .'TODAY(h) : '.'<span class="hour">'.$row['today'].'</span>'.'<br>'
				.'TOTAL(h) : '.'<span class="hour">'.$row['total'].'</span>'.'<br>'
            .$row['contents'].'<br>'; 
           ?>
        </div>
    <?php
   
     }
//     pagination($currentPageNum, $dbProductData['tota_page']); 
        
        ?>
     
  
   </section>
    
</body>
</html>


<!--
<section class="past">
    <h2 class="icon">投稿</h2>
<div class="form">
-->

