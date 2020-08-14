<?php
require_once '../conf/const.php';
require_once '../model/user.php';
require_once '../model/purchase_log.php';

session_start();

$db = get_db_connect();
$user_id = $_SESSION['user_id'];

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$user = get_login_user($db);
$order_id = $_POST['purchase_id'];
$purchase_data = get_purchase_data($db, $order_id);
if(is_admin($user) || $purchase_data['user_id'] === $user_id){
  $purchase_contents_data = get_purchase_contents($db, $order_id);
}else{
  set_error('不正な操作です。購入履歴の表示に失敗しました。');
  $purchase_data = array();
  $purchase_contents_data = array();
}


include_once VIEW_PATH . 'purchase_contents_view.php';