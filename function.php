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
define('MSG06','');

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
function



?>