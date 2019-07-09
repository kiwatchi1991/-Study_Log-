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


// ページネーション
//================================================================
// カレントページ
$currentPageNum = (!empty($_GET['p'])) ? (int)$_GET['p'] : 1; //デフォルトは１ページ目
//カテゴリー
debug('デバック■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■■'.print_r(gettype($currentPageNum),true));
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
$listSpan = 3;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan); //１ページ目なら(1-1)*20=0,2ページ目なら(２−１)*20=20

//DBから商品データを取得
$dbProductData = getDataList($currentMinNum,  $sort, $listSpan);









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
       <h3><a href="article.php">記事一覧へ <i class="far fa-hand-point-right"></i></a></h3>
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
        
        
        
        
        
        
        
        
    <div class="panel-list">
      
       <div class="top-icon">
          <h2 class="icon">最新の投稿</h2>
          <h3><a href="article.php">記事一覧へ <i class="far fa-hand-point-right"></i></a></h3>
       </div>
        
        
        <?php 
        foreach($dbProductData['data'] as $key => $val):
        ?>
        <?php echo '<div class="panel-body '.$val['data_id'].' ">';       ?>

        <div class="icon tweet">
          <?php 
          $int = (int)$val['data_id'];
          Tweet($int);
          ?>
        </div>
        <!--            編集アイコン-->
        <div class="icon edit"><a href="index.php<?php echo '?d_id='.$val['data_id']; ?>"><i class="fas fa-edit"></i></a></div>
        <!--            削除アイコン-->
        <div class="icon delete">
          <!--           //aタグにコンフfァームメッセージ-->


          <a href="article.php<?php echo '?d_id='.$val['data_id']; ?>" onclick="return confirm('<?php echo sanitize($val['date']); ?>\n<?php echo sanitize($val['today']); ?>\n<?php echo sanitize($val['total']); ?>\n<?php echo sanitize($val['contents']); ?>\n\nこの投稿を削除しますか？'); ">
            <i class="far fa-trash-alt"></i></a></div>

        <div class="date">
          <?php echo sanitize($val['date']); ?>
        </div>

        <div class="studied-time">
          <div class="today">
            today： <span class="hour"><?php echo sanitize($val['today']); ?></span> h
          </div>
          <div class="total">
            total :   <span class="hour"><?php echo sanitize($val['total']); ?></span> h
          </div>
        </div>

        <div class="contents">
          <pre><?php echo sanitize($val['contents']); ?></pre>
        </div>

      </div>
      <?php 
      endforeach;
      ?>

        
        
        
        
        
        
        
     </section>
       
</div>
    
<!--フッター-->
<?php 
require('footer.php');
  ?>

</body>
</html>

