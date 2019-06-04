<?php 

error_reporting(E_ALL);//エラー報告
ini_set('display_errors','On');//ディスプレイ表示
//================================
// ログ
//================================
//ログを取るか
ini_set('log_errors','on');
//ログの出力ファイルを指定
ini_set('error_log','php.log');
//================================
    // デバッグ
    //================================
    //デバッグフラグ
    $debug_flg = true;
//デバッグログ関数
function debug($str){
    global $debug_flg;
    if(!empty($debug_flg)){
        error_log('デバッグ：'.$str);
    }
}


//post送信されていた場合
if(!empty($_POST)){
//エラーメッセージ
    define('MSG01','入力必須です');

    //配列$err_msgを用意
    $err_msg = array();

//フォームが入力されていない場合
    if(empty($_POST['date'])){
        
        $err_msg['date'] = MSG01;
        
    }
    if(empty($_POST['day'])){

        $err_msg['day'] = MSG01;

    }
    if(empty($_POST['total'])){

        $err_msg['total'] = MSG01;

    }
    if(empty($_POST['contents'])){

        $err_msg['contents'] = MSG01;

    }
    
    if(empty($err_msg)){
        
        //変数に代入　本来はサニタイズ必要
        $date = htmlspecialchars($_POST['date'],ENT_QUOTES);
        $day = htmlspecialchars($_POST['day'],ENT_QUOTES);
        $total = htmlspecialchars($_POST['total'],ENT_QUOTES);
        $contents = htmlspecialchars($_POST['contents'],ENT_QUOTES);
        
    
    
    if(empty($err_msg)){

    //DBへの接続準備
    $dsn = 'mysql:dbname=study;host=localhost;charset=utf8';
    $user = 'kiwatchi1991';
    $password = 'orange1212';
    $options = array(
        // SQL実行失敗時に例外をスロー
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // デフォルトフェッチモードを連想配列形式に設定
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
    PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
  );
    
    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);
    
    //SQL文（クエリー作成）
    $stmt = $dbh->prepare('INSERT INTO data (date,day,total,contents) VALUES (:date,:day,:total,:contents) ');

//プレースホルダーに値をセットし、SQL文を実行（サーバーにデータを保存）
    $stmt->execute(array(':date' => $date,':day'=> $day,':total'=> $total, ':contents'=> $contents));
        
        header("Location:index.php");
    }

  }
}
        

?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="/study/style.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
    <title>HOME | $Study.log() </title>
</head>
<body>
   
    
<!--ヘッダー-->
  <header>
     <div class="site-width">
       <h1><a href="index.php"> $Study.Log() </a></h1>
     </div>
  </header>

<!--   メインコンテンツ-->
   <div id="contents" class="site-width">
<!--       投稿-->
        <section class="today">
           <h2 class="icon">入力</h2>
           <div class="form">
               <form action="" method="post">
                   <dl>
                      <span class="err_msg"><?php if(!empty($err_msg['date'])) echo $err_msg['date']; ?></span>
                       <dt><span class="required">勉強した日</span></dt>
                       
                       <dd><input type="text" name="date" class="date" required value="<?php if(!empty($_POST['date'])) echo $_POST['date']; ?>"></dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['day'])) echo $err_msg['day']; ?></span>
                       <dt><span class="required">day</span></dt>
                       
                       <dd><input type="text" name="day" class="day" required value="<?php if(!empty($_POST['day'])) echo $_POST['day']; ?>"></dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['total'])) echo $err_msg['total']; ?></span>
                       <dt><span class="required">total(h)</span></dt>
                       
                       <dd><input type="text" name="total" class="total" required value="<?php if(!empty($_POST['total'])) echo $_POST['total']; ?>"></dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['contents'])) echo $err_msg['contents']; ?></span>
                       <dt><span class="required">内容</span></dt>
                       
                       <dd><textarea name="contents"  cols="50" rows="10" required value="<?php if(!empty($_POST['contents'])) echo $_POST['contents']; ?>"></textarea></dd>
                      
                   </dl>
                   <button type="submit" class="btn">投稿</button>
                   
               </form>
           </div>
       </section>
       
    </div>
    
    <?php 
    

    //        DBからデータを取得
    //DBへの接続準備
    $dsn = 'mysql:dbname=study;host=localhost;charset=utf8';
    $user = 'kiwatchi1991';
    $password = 'orange1212';
    $options = array(
        // SQL実行失敗時に例外をスロー
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // デフォルトフェッチモードを連想配列形式に設定
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
        // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );

    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);

    //        DBからデータを取得
    //        1.テーブルにある全てのデータを取得するSQL文を、変数に格納
    $sql = "SELECT * FROM data order by data_id desc";
    //        2.SQL文を実行するコードを、変数に格納
    $stmt1 = $dbh->query($sql);
    //        3.foreach文でデータベースより取得したデータを１行ずるループ処理（連想配列で取得したデータのうち、１行文が$rowに格納
    
          
           
    foreach($stmt1 as $row){
        //        4.連想配列形式の1行のデータから、キーを指定し、出力する
  ?>
        <section class="past">
        <?php 
            echo 
            $row['date'].'<br>'
            .$row['day'].'<br>'
            .$row['total'].'<br>'
            .$row['contents'].'<br>'; 
        ?>
        </section>
     <?php
   }
    ?>
    
    
</body>
</html>


<!--
<section class="past">
    <h2 class="icon">投稿</h2>
<div class="form">
-->

