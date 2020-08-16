<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';
require_once '../model/category.php';
require_once '../model/cookie.php';

session_start();
reset_cookies();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$page_title = '新着商品';
$page_num = page_num($_GET['page']);

//カテゴリー一覧
$categories = get_all_categories($db);

//単に取得したitems
$items = get_open_items($db,$page_num);
//アイテム総数
$all_items = get_items($db,true);

//表示アイテム
if(isset($_GET['category']) === TRUE){
  $get_category = category($db,$page_num,$categories);
  $items = $get_category['items'];
  $all_items = $get_category['all_items'];
  $page_title = $get_category['page_title'];
}
//ワード検索機能
if(isset($_GET['search_word']) === TRUE){
  $get_search_word = search_word($db,$page_num);
  $items = $get_search_word['items'];
  $all_items = $get_search_word['all_items'];
  $page_title = $get_search_word['page_title'];
}

//総ページ数
$pages = ceil(count($all_items)/DISPLAY_LIMIT);
$count_item_num = count_item_num($page_num, $pages, $items);
$item_first_child_num = $count_item_num[0];
$item_last_child_num = $count_item_num[1];

if(isset($_COOKIE['category']) === TRUE){
  $cookie = '&?category='.$_COOKIE['category'];
}elseif(isset($_COOKIE['search_word']) ===TRUE){
  $cookie = '&?search_word='.$_COOKIE['search_word'];
}else{
  $cookie = "";
}

$csrf_token = csrf_token();
include_once VIEW_PATH . 'index_view.php';