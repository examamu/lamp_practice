<?php 
require_once '../conf/const.php';
require_once '../model/user.php';
require_once '../model/purchase_log.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
$db = get_db_connect();
$user_id = $_SESSION['user_id'];
// $user = get_login_user($db);
$user = get_login_user($db);
if(is_admin($user)){
  $purchases = get_purchase_all($db);
}else{
  $purchases = get_purchase($db, $user_id);
}
include_once VIEW_PATH . 'purchase_log_view.php';