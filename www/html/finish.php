<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$carts = get_user_carts($db, $user['user_id']);

try{
  $db->beginTransaction();

  //購入時に在庫数を一つ減らしカートから削除する処理
  purchase_carts($db, $carts);
  $user_id = $user['user_id'];
  $purchase_data = new Order($db, $user_id);
  $purchase_data->insert_order_historys($carts);
  $db->commit();
}catch(PDOException $e){
  $db->rollBack();
  set_error('商品の購入に失敗しました。再度お試しください。');
  $e->getMessage();
  redirect_to(CART_URL);
}


$total_price = sum_carts($carts);

include_once '../view/finish_view.php';