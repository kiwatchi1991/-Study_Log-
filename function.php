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

//================================
// 画面表示処理開始ログ吐き出し関数
//================================
function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>.画面表示処理開始');
//    debug('セッションID：'.session_id());
//    debug('セッション変数の中身：'.print_r($_SESSION,true));
    debug('現在日時タイムスタンプ：'.time());
//    if(!empty($_SESSION['login_date'])) && !empty($_SESSION['login_limit']){
//        debug('ログイン期日日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit'] ) );
    }
    
//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02','半角数字のみ御利用頂けます');
define('MSG03','時間の形式が違います');
define('MSG04','');
define('MSG05','');
define('MSG06','エラーが発生しました。しばらく経ってからもう一度お試しください。');

//配列$err_msgを用意
$err_msg = array();

//================================
// バリデーション関数
//================================

//バリデーション関数（未入力チェック）
function validRequired($str, $key){
    if($str === ''){ //金額フォームなどを考えると数値の０はOKにし、空文字はダメにする
     global $err_msg;
     $err_msg[$key] = MSG01;
    }
}

//バリデーション関数（半角数字チェック）
function validNumber($str, $key){
    if(!preg_match("/^[0-9]+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}

//エラーメッセージ表示
function getErrMsg($key){
    global $err_msg;
    if(!empty($err_msg['key'])){
        return $err_msg[$key];
    }
}    


//================================
// DB接続関数
//================================

//DB接続
function dbConnect(){
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
    
    return $dbh;
}

//SQL実行関数
function queryPost($dbh,$sql,$data){
//作成したSQL文（$sql）を用意し、クエリ作成
    $stmt = $dbh ->prepare($sql);
//    プレースホルダーに値をセットし、SQL文を実行
    if(!$stmt->execute($data)){
        debug('クエリに失敗しました。');
        debug('失敗したSQL：'.print_r($stmt,true));
        $err_msg['common'] = MSG07;
        return 0;
    }
        debug('クエリ成功。');
        return $stmt;
}

function getDataList($currentMinNum = 1, $sort, $span = 20){
    debug('データ取得します');
    //例外
    try{
        //DBへ接続
        $dbh = dbConnect();
//        件数用のSQL文作成
        $sql = 'SELECT * FROM data order by data_id desc';
        
    $data = array();
//    クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $rst['total'] = $stmt->rowCount();
    $rst['total_page'] = ceil($rst['total']/$span); //総ページ数
    if(!$stmt){
        return false;
    }
    
   
        
    ///ページング用のSQL文作成
    $sql = 'SELECT * FROM data order by data_id desc';
    $sql .=' LIMIT '.$span.' OFFSET '.$currentMinNum ;
    $data = array();
    debug('SQL:'.$sql);
//    クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

        debug('???????????????デバック表示???????????????/:' .print_r($stmt,true));
        
        
    if($stmt){
//        クエリ結果のデータを全レコードを格納
        $rst['data'] = $stmt->fetchAll();
        return $rst;
    }else{
        return false;
    }
    
    }catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
    }
}



//================================
// ページング
//================================
// $currentPageNum : 現在のページ数
// $totalPageNum : 総ページ数
// $link : 検索用GETパラメータリンク
// $pageColNum : ページネーション表示数
function pagination( $currentPageNum, $totalPageNum, $pageColNum= 5){
//    現在のページが、総ページ数と同じかつ、総ページ数が表示項目数以上なら、左にリンク４個出す
    if( $currentPageNum == $totalPageNum && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 4;
        $maxPageNum = $currentPageNum;
//        現在ページが、総ページ数の１ページ前なら、左にリンク３個、右に１個出す
    }elseif($currentPageNum == ($totalPageNum - 1) && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 3;
        $maxPageNum = $currentPageNum + 1;
//    現在ページが２の場合は左にリンク１個、右にリンク３個出す
    }elseif($currentPageNum == 2 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum - 1;
        $maxPageNum = $currentPageNum + 3;
//        現在ページが１の場合は左に何も出さない。右に５個出す
    }elseif($currentPageNum == 1 && $totalPageNum > $pageColNum){
        $minPageNum = $currentPageNum;
        $maxPageNum = 5;
//    総ページ数が表示項目数より少ない場合は、総ページ数をループのMax、ループのMinを１に設定
    }elseif($totalPageNum < $pageColNum){
        $minPageNum = 1;
        $maxPageNum = $totalPageNum;
//        それ以外は左右に２個出す。
    }else{
        $minPageNum = $currentPageNum -2;
        $maxPageNum = $currentPageNum +2;
    }

    echo   '<div class="pagination">';
      echo '<ul  class="pagination-list">';
        if($currentPageNum !== 1){
            echo '<li class="list-item"><a href="?pp=1">&lt;</a></li>';
        }
        for($i = $minPageNum; $i <= $maxPageNum; $i++){
            echo '<li class="list-item ';
            if($currentPageNum == $i ){ echo 'active'; }
            echo  '"><a href="?p='.$i.'">'.$i.'</a></li>';
        }
        if($currentPageNum != $maxPageNum && $maxPageNum > 1){
            echo '<li class="list-item"><a href="?p='.$maxPageNum.'">&gt;</a></li>';
        }
      echo '</ul>';
    echo '</div>';
}


//================================
// tweet関数
//================================
//

function Tweet($int){
    debug('tweet用データ取得します');
    debug('???????????????デバック表示222???????????????/:' .print_r($int,true));
    //例外
    try{
        //DBへ接続
        $dbh = dbConnect();
        //       SQL文作成
        $sql = 'SELECT * FROM data WHERE data_id = "$int" ';

        $data = array();
        //    クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        
        echo $int ;
        debug('???????????????デバック表示???????????????/:' .print_r($int,true));
        
        foreach($stmt as $row){
            //        4.連想配列形式の1行のデータから、キーを指定し、出力する

//            echo    '勉強した日  '.$row['date'].'<br>'
//                .'today(h)  '.$row['today'].'<br>'
//                .'total(h)  '.$row['total'].'<br>'
//                .'内容  '.$row['contents'].'<br>'; 
//  
                $DATE = $row['date'];
                $TODAY = 'today : '.$row['today'];
                $TOTAL = 'total : '.$row['total'];
                $CONTENTS = $row['contents'];
            
            $str = $TODAY.'h%0A'.$TOTAL.'h%0A%0A'.$CONTENTS ;
            
          
            echo '<a href="https://twitter.com/intent/tweet?text='.$str.'" 
                target="_blank"><img src="img/icon_1.png" alt="tweet" title="tweet" height="25px" width="25px"></a>' ;
        }
        
     
    }catch (Exception $e) {
        error_log('エラー発生：' . $e->getMessage());
    }
}



//================================
// その他
//================================
//サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
}





//define('TWEET','<a href="https://twitter.com/intent/tweet?text='.$str.'" 
// target="_blank"><img src="img/icon_1.png" alt="tweet" title="tweet" height="25px" width="25px"></a>');

//ツイートアイコン表示
//function tweet(){
//    
//    
//    
//    $str = "day%20:%20".. ;
//    
//    echo '<a href="https://twitter.com/intent/tweet?text='.$str.'" 
// target="_blank"><img src="img/icon_1.png" alt="tweet" title="tweet" height="25px" width="25px"></a>' ; 
//}
//
//function tweet(){
//
//    echo TWEET ; 
//}

//getDataForTweet() ;

//today%20:%201%20h%0Atotal%20:%201%20h%0Awertwret
    
?>