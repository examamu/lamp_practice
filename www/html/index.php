<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$page_num = page_num($_GET['page']);

$items = get_open_items($db,$page_num);
$all_items = get_items($db,true);
$pages = count($all_items)/DISPLAY_LIMIT+1;

$count_item_num = count_item_num($page_num, $pages, $items);

$item_first_child_num = $count_item_num[0];
$item_last_child_num = $count_item_num[1];

$csrf_token = csrf_token();
include_once VIEW_PATH . 'index_view.php';