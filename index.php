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

    //変数に代入
    $date =  sanitize($_POST['date']);
    $today = sanitize($_POST['today']);
    $total = sanitize($_POST['total']);
    $contents = sanitize($_POST['contents']);

//フォームが入力されていない場合

    //勉強した日・today・total の形式チェック
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
    
     <div class="top-icon">
        <h2 class="icon">投稿</h2>
        <h3><a href="article.php">&gt;&gt;記事一覧へ</a></h3>
     </div>
       
        <div class="form">
          <form action="" method="post">
            <dl>
              <!--date-->
              <div id="err_msg">
                <span class="err_msg"><?php if(!empty($err_msg['date'])) echo $err_msg['date']; ?></span>
              </div>
              <dt><span class="">DATE</span></dt>
              
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
                       
              <!-- Contents-->
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
    

</body>
</html>

