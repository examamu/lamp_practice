<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="index_container">
    <?php include VIEW_PATH . 'templates/sidebar.php'; ?>

    <main id = "index_main">
    <!--メインイメージ-->
    <div class = "slider_area">
      <div class = "main_img full_screen slider">
        <div><img class = "swiper-slide" src = "<?php print (IMAGE_PATH . 'IoTIMGL3531_TP_V.jpg'); ?>"></div>
        <span>パーツ販売サイト PartsMarket</span>
      </div>
    </div>

    <h1><?php echo $page_title ?></h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <div class="item_container">
      <?php foreach($items as $item){ ?>
        <div class="item_wrapper">
            <div class="item_name">
              <?php print(h($item['name'])); ?>
            </div>
            <figure>
              <img class="item_img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
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
      <?php } ?>
    </div>
<?php include VIEW_PATH . 'templates/pagination.php'; ?>
    </main>
  </div>

<?php include VIEW_PATH . 'templates/footer.php'; ?>