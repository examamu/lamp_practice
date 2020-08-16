<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";
  $params = array(
    array(1,$item_id,'int')
  );
  return fetch_query($db, $sql, $params);
}



function get_items($db, $is_open = false){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';
  if($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }
  $params = array();

  return fetch_all_query($db, $sql, $params);
}



function get_items_limit($db,$page_num){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      status = 1
    LIMIT ?
    OFFSET ?
  ';
  $params = array(
    array(1,DISPLAY_LIMIT,'int'),
    array(2,$page_num,'int')
    
  );

  return fetch_all_query($db, $sql, $params);
}



function get_category_all_items($db,$category_id){
  $sql = "
    SELECT
      item_id, items.name, stock, price, image, status
    FROM
      items
    JOIN
      categories
    ON
      items.category_id = categories.id
    WHERE
      status = 1
    AND
      categories.id = ?
    ";

  $params = array(
    array(1,$category_id,'int')
  );

  return fetch_all_query($db, $sql, $params);
}



function get_category_items($db,$page_num,$category_id){
  $sql = "
    SELECT
      item_id, items.name, stock, price, image, status
    FROM
      items
    JOIN
      categories
    ON
      items.category_id = categories.id
    WHERE
      status = 1
    AND
      categories.id = ?
    LIMIT ?
    OFFSET ?";

  if($page_num === 1){
    $page_num = 0;
  }
  $params = array(
    array(1,$category_id,'int'),
    array(2,DISPLAY_LIMIT,'int'),
    array(3,$page_num,'int')
  );

  return fetch_all_query($db, $sql, $params);
}



function get_search_word_items($db,$page_num,$search_word){
  $sql = '
    SELECT
      item_id, name, stock, price, image, status
    FROM
      items
    WHERE
      status = 1
    AND
      name
    LIKE ?
    LIMIT ?
    OFFSET ?
  ';
  if($page_num === 1){
    $page_num = 0;
  }
  $params = array(
    array(1,'%'.$search_word.'%','str'),
    array(2,DISPLAY_LIMIT,'int'),
    array(3,$page_num,'int')
  );
  return fetch_all_query($db, $sql, $params);
}

function get_search_word_all_items($db,$search_word){
  $sql = '
    SELECT
      item_id, name, stock, price, image, status
    FROM
      items
    WHERE
      status = 1
    AND
      name
    LIKE ?
  ';

  $params = array(
    array(1,'%'.$search_word.'%','str')
  );
  return fetch_all_query($db, $sql, $params);
}



function get_all_items($db){
  return get_items($db);
}



function get_open_items($db,$page_num){
  $page_num = ($page_num - 1) * DISPLAY_LIMIT;
  return get_items_limit($db,$page_num);
}



function regist_item($db, $name, $category_id, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $category_id, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $category_id, $price, $stock, $status, $image, $filename);
}



function regist_item_transaction($db, $name, $category_id, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $category_id, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}



function insert_item($db, $name, $category_id, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        category_id,
        price,
        stock,
        image,
        status
      )
    VALUES(?,?,?,?,?,?)
  ";
  $params = array(
    array(1,$name,'str'),
    array(2,$category_id,'int'),
    array(3,$price,'int'),
    array(4,$stock,'int'),
    array(5,$filename,'str'),
    array(6,$status_value,'int')
  );
  return execute_query($db, $sql, $params);
}



function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  $params = array(
      array(1,$status,'int'),
      array(2,$item_id,'int')
    );
  return execute_query($db, $sql, $params);
}



function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
  $params = array(
    array(1,$stock,'int'),
    array(2,$item_id,'int')
  );
  return execute_query($db, $sql, $params);
}



function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}



function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";
  $params = array(
      array(1,$item_id,'int')
  );
  return execute_query($db, $sql, $params);
}






// 非DB

function is_open($item){
  return $item['status'] === 1;
}



function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}



function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}



function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}



function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}



function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}



function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}





function page_num($get_page){
  if(isset($get_page) === TRUE){
    if(is_valid_page_num($get_page) === TRUE){
      $page_num = intval($get_page);
    }else{
      $page_num = 1;
    }
  }else{
    $page_num = 1;
  }
  return $page_num;
}



function is_valid_page_num($page_num){
  $is_valid = true;
  if(preg_match(REGEXP_POSITIVE_INTEGER,$page_num) === 0){
    set_error('ページがありません指定をもう一度ご確認ください');
    $is_valid = false;
  }
  return $is_valid;
}



function count_item_num($page_num,$pages,$items){
  if($page_num <= $pages && $page_num > 0){
    if($page_num === 1){
      $item_first_child_num = 1;
    }else{
      $item_first_child_num = ($page_num -1) * DISPLAY_LIMIT +1;
    }
    $item_last_child_num = $item_first_child_num + count($items) -1;
  }else{
    $item_first_child_num = '';
    $item_last_child_num = '';
    set_error('ページがありません指定をもう一度ご確認ください');
  }
  $count_item_num = array($item_first_child_num, $item_last_child_num);

  return $count_item_num;
}



function active_page($page_num,$i){
  if($page_num === $i){
    return 'active';
  }else{
    return '';
  }
}



function before_page_button($page_num){
  return "./?page=".($page_num - 1);
}



function next_page_button($page_num){
  return "./?page=".($page_num + 1);
}







?>