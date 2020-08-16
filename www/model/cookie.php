<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function reset_cookies(){
    if($_SERVER['REQUEST_URI'] === "/" || $_SERVER['REQUEST_URI'] === "/index.php" || $_SERVER['REQUEST_URI'] === "/?search_word="){
        setcookie('search_word',$_GET['search_word'],time()-1);
        setcookie('category',$_GET['category'],time()-1);
    };
}


?>