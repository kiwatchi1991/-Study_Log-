<?php 

//共通変数・関数ファイルを読込み
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('　「　トップページ　」　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


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
    validNumber($date,'date');
    validNumber($today,'today');
    validNumber($total,'total');
    
    
    //    未入力チェック
    validRequired($date, 'date');
    validRequired($today, 'today');
    validRequired($total, 'total');
    validRequired($contents, 'contents');
    
    if(empty($err_msg)){
      debug('バリデーションOKです。');

//        DBへ接続
    $dbh = dbConnect();
    
//    SQL文(クエリー作成)
    $sql = 'INSERT INTO data (date,today,total,contents) VALUES (:date,:today,:total,:contents) ';

//プレースホルダーに値をセットし、SQL文を実行（サーバーにデータを保存）
    $data = array(':date' => $date,':today'=> $today,':total'=> $total, ':contents'=> $contents);
        
    $stmt = queryPost($dbh,$sql,$data);
        
        header("Location:index.php");
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
           <h2 class="icon">投稿</h2>
           <div class="form">
               <form action="" method="post">
                   <dl>
                      <span class="err_msg"><?php if(!empty($err_msg['date'])) echo $err_msg['date']; ?></span>
                       <dt><span class="">勉強した日</span></dt>
                       
                     <dd>
                       <input type="text" name="date" class="date" value="<?php if(!empty($_POST['date'])) echo $_POST['date']; ?>">
<!--
                           <div class="area-msg">
                               <?php if(!empty($err_msg['date'])) echo $err_msg['date'];?>
                           </div>
-->
                     </dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['today'])) echo $err_msg['today']; ?></span>
                       <dt><span class="">today(h)</span></dt>
                       
                       <dd><input type="text" name="today" class="day" value="<?php if(!empty($_POST['today'])) echo $_POST['today']; ?>"></dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['total'])) echo $err_msg['total']; ?></span>
                       <dt><span class="">total(h)</span></dt>
                       
                       <dd><input type="text" name="total" class="total"  value="<?php if(!empty($_POST['total'])) echo $_POST['total']; ?>"></dd>
                       
                       <span class="err_msg"><?php if(!empty($err_msg['contents'])) echo $err_msg['contents']; ?></span>
                       <dt><span class="">内容</span></dt>
                       
                       <dd><textarea name="contents"  cols="50" rows="10"  value="<?php if(!empty($_POST['contents'])) echo $_POST['contents']; ?>"></textarea></dd>
                      
                   </dl>
                   <button type="submit" class="btn">送信</button>
                   
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
    
    ?>
    <section class="past">
        <h2 class="icon">記録</h2>
        <?php 
    foreach($stmt1 as $row){
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
    
</body>
</html>


<!--
<section class="past">
    <h2 class="icon">投稿</h2>
<div class="form">
-->

