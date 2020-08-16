<?php
require_once '../conf/const.php';
require_once '../model/functions.php';
require_once '../model/user.php';
require_once '../model/item.php';
require_once '../model/category.php';
require_once '../model/cookie.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$page_title = '新着商品';
$page_num = page_num($_GET['page']);

//カテゴリー一覧
$categories = get_all_categories($db);


//表示アイテム
if(isset($_GET['category']) === TRUE){
  $page_title = $categories[$_GET['category']-1]['name'];
  setcookie('category',$_GET['category'],time()+60+60);
  setcookie('search_word',$_GET['search_word'],time()-1);
  //カテゴリー仕分けしたitems
  $items = get_category_items($db,$page_num,$_GET['category']);
  //アイテム総数
  $all_items = get_category_all_items($db,$_GET['category']);
}else{
  //単に取得したitems
  $items = get_open_items($db,$page_num);
  //アイテム総数
  $all_items = get_items($db,true);
}

//ワード検索機能
if(isset($_GET['search_word']) === TRUE){
  setcookie('category',$_GET['category'],time()-1);
  setcookie('search_word',$_GET['search_word'],time()+60+60);
  $search_word = $_GET['search_word'];
  $items = get_search_word_items($db,$page_num,$search_word);
  $all_items = get_search_word_all_items($db,$search_word);
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
  $cookie = "&?search_word=";
}

$csrf_token = csrf_token();
include_once VIEW_PATH . 'index_view.php';

echo $cookie;