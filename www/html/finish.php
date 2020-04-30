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

  purchase_carts($db, $carts);
  $user_id = $user['user_id'];
  $purchase_data = new Order($db, $user_id);
  $purchase_data->insert_order_historys();
  $order_id = $db->lastInsertId('id');

//オーダーIDを取得
  foreach($carts as $cart){
    $item_id = $cart['item_id'];
    $order_price = $cart['price'];
    $amount = $cart['amount'];
    $purchase_data->insert_order_item_historys($order_id,$item_id,$order_price,$amount);
  }
  $db->commit();
}catch(PDOException $e){
  $db->rollBack();
  set_error('商品の購入に失敗しました。再度お試しください。');
  $e->getMessage();
  redirect_to(CART_URL);
}


$total_price = sum_carts($carts);

include_once '../view/finish_view.php';