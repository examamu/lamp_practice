<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'purchase_contents.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
<?php include VIEW_PATH . 'templates/messages.php'; ?>
    <table class ="table-bordered col-lg-12 mb-5">
      <thead>
        <tr>
          <th>購入ID</th>
          <th>購入日時</th>
          <th>購入合計金額</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><?php echo $purchase_data['id']; ?></td>
          <td><?php echo $purchase_data['order_datetime'];?></td>
          <td><?php echo $purchase_data['total_price']; ?></td>
        </tr>
      </tbody>
    </table>

    <table class = "table-bordered col-lg-12">
      <tr>
        <th>商品名</th>
        <th>購入時の商品価格</th>
        <th>購入数</th>
        <th>小計</th>
      </tr>
<?php foreach ($purchase_contents_data as $data){ ?>
      <tr>
        <td><?php echo h($data['name']); ?></td>
        <td><?php echo $data['order_price']; ?></td>
        <td><?php echo $data['amount']; ?></td>
        <td><?php echo $data['order_price']*$data['amount']; ?></td>
      </tr>
<?php } ?>
    </table> 
  </div>
  
</body>
</html>