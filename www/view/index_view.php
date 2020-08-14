<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  

  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print(h($item['name'])); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  <nav>
    <p><?php echo count($all_items); ?>件中 <?php echo $item_first_child_num; ?> - <?php echo $item_last_child_num; ?>件目の商品を表示中</p>
      <ul class = "pagination">

<?php if($page_num <= 1){?>
        <li class = "page-item disabled">
          <span class="page-link">前へ</span>
        </li>
<?php }else{ ?>
        <li class = "page-item">
          <a class="page-link" href = "<?php echo before_page_button($page_num); ?>">前へ</a>
        </li>
<?php } ?>

<?php for($i = 1; $i<=$pages; $i++){ ?>
        <li class = "page-item <?php echo active_page($page_num,$i) ?>">
  <?php if( $page_num === $i) { ?>
            <span class="page-link "><?php echo $i ?></span>
  <?php }else{ ?>
          <a class="page-link" href = "./?page=<?php echo $i ?>" >
            <?php echo $i ?>
          </a>
  <?php } ?>
        </li>
<?php } ?>

<?php if(count($all_items) === $item_last_child_num){?>
        <li class = "page-item disabled">
        <span class="page-link">次へ</span>
        </li>
<?php }else{ ?>
        <li class = "page-item">
          <a class="page-link" href = "<?php echo next_page_button($page_num)?>">次へ</a>
        </li>
<?php } ?>
      </ul>
  </nav>
</body>
</html>