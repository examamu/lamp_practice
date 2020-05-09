<?php 
    require_once MODEL_PATH . 'functions.php';
    require_once MODEL_PATH . 'db.php';
    
    function get_purchase_all($db){
        $sql = "SELECT 
                order_historys.id, 
                order_historys.order_datetime, 
                SUM(order_item_historys.order_price*order_item_historys.amount) AS total_price
                FROM order_historys 
                JOIN order_item_historys 
                ON order_historys.id = order_item_historys.order_id 
                GROUP BY order_historys.id, order_historys.order_datetime 
                ORDER BY order_historys.order_datetime DESC
                ";
        $params = array();
    return fetch_all_query($db, $sql, $params);
    }

    function get_purchase($db, $user_id) {
        $sql = "SELECT 
                order_historys.id, 
                order_historys.order_datetime, 
                SUM(order_item_historys.order_price*order_item_historys.amount) AS total_price
                FROM order_historys 
                JOIN order_item_historys 
                ON order_historys.id = order_item_historys.order_id 
                WHERE order_historys.user_id = ? 
                GROUP BY order_historys.id, order_historys.order_datetime 
                ORDER BY order_historys.order_datetime DESC
                ";
        $params = array(
            array(1,$user_id,'int')
        );
    return fetch_all_query($db, $sql, $params);
    }

    function get_purchase_data($db, $order_id) {
        $sql = "SELECT 
                order_historys.id, 
                order_historys.order_datetime,
                order_historys.user_id,
                SUM(order_item_historys.order_price*order_item_historys.amount) AS total_price
                FROM order_historys 
                JOIN order_item_historys 
                ON order_historys.id = order_item_historys.order_id 
                WHERE order_historys.id = ? 
                GROUP BY order_historys.id, order_historys.order_datetime, order_historys.user_id
                ";
        $params = array(
            array(1,$order_id,'int')
        );
    return fetch_query($db, $sql, $params);
    }

    function get_purchase_contents($db, $order_id){
        //「商品名」「購入時の商品価格」 「購入数」「小計」の取得
        $sql = 'SELECT
                order_id,
                items.item_id,
                order_price,
                amount,
                items.name
                FROM 
                order_item_historys
                JOIN items
                ON order_item_historys.item_id = items.item_id
                WHERE order_id = ?
                ';
        $params = array(
            array(1,$order_id,'int')
        );
        return fetch_all_query($db, $sql, $params);
    }

?>