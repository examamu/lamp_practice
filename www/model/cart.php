<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";
  $params = array(
    array(1,$user_id,'int')
  );
  return fetch_all_query($db, $sql, $params);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";
  $params = array(
    array(1,$user_id,'int'),
    array(2,$item_id,'int')
  );
  return fetch_query($db, $sql, $params);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";
  $params = array(
    array(1,$item_id,'int'),
    array(2,$user_id,'int'),
    array(3,$amount,'int')
  );
  return execute_query($db, $sql, $params);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  $params = array(
    array(1,$amount,'int'),
    array(2,$cart_id,'int')
  );
  return execute_query($db, $sql, $params);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";
  $params = array(
      array(1,$cart_id,'int')
  );
  return execute_query($db, $sql, $params);
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
  $params = array(
    array(1,$user_id,'int')
  );
  execute_query($db, $sql, $params);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

Class Order{

  private $db;
  private $user_id;
  private $order_id;
  private $item_id;
  private $order_price;
  private $amount;
  

  function __construct($db,$user_id)
  {
    $this->db = $db;
    $this->user_id = $user_id;
  }

  public function insert_order_historys(){
    $sql = "INSERT INTO order_historys(user_id,order_datetime) VALUES(?,?)";
    $datetime = date('YmdHis');
    $params = array(
        array(1,$this->user_id,'int'),
        array(2,$datetime,'str')
    );
    return execute_query($this->db, $sql, $params);  
  }

  public function insert_order_item_historys($order_id,$item_id,$order_price,$amount){
    $sql = "INSERT INTO order_item_historys(order_id,item_id,order_price,amount) VALUES(?,?,?,?)";
    $this->order_id = $order_id;
    $this->item_id = $item_id;
    $this->order_price = $order_price;
    $this->amount = $amount;
    $params = array(
      array(1,$this->order_id,'int'),
      array(2,$this->item_id,'int'),
      array(3,$this->order_price,'int'),
      array(4,$this->amount,'int')
  );
  return execute_query($this->db, $sql, $params);
  }

}