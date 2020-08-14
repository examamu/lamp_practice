<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'purchase_log.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>購入履歴</h1>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
      <table class = " table-bordered col-lg-12">
          <tr>
              <th>注文番号</th>
              <th>購入日時</th>
              <th>購入合計金額</th>
          </tr>
          
<?php foreach($purchases as $data){ ?>
          <form method = "POST" action = "./purchase_contents.php">
          <input type = "hidden" name = "purchase_id" value = <?php echo $data['id'] ?> >
          <tr>
              <td><?php echo $data['id'] ?></td>
              <td><?php echo $data['order_datetime'] ?></td>
              <td><?php echo $data['total_price'] ?></td>
              <td><input type = "submit" name = "purchase_submit<?php echo $data['id']?>" value = "購入明細表示"></td>
          </tr>
          </form>
<?php } ?>
      </table>
      
  </div>
  
</body>
</html>