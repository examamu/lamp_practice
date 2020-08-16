<nav class="pagination_nav">
    <p><?php echo count($all_items); ?>件中 <?php echo $item_first_child_num; ?> - <?php echo $item_last_child_num; ?>件目の商品を表示中</p>
      <ul class="pagination">

<?php if($page_num <= 1){?>
        <li class = "page-item disabled">
          <span class="page-link">前へ</span>
        </li>
<?php }else{ ?>
        <li class = "page-item">
          <a class="page-link" href = "<?php echo before_page_button($page_num); ?><?php echo $cookie; ?>">前へ</a>
        </li>
<?php } ?>

<?php for($i = 1; $i<=$pages; $i++){ ?>
        <li class = "page-item <?php echo active_page($page_num,$i) ?>">
  <?php if( $page_num === $i) { ?>
            <span class="page-link "><?php echo $i ?></span>
  <?php }else{ ?>
          <a class="page-link" href = "./?page=<?php echo $i ?><?php echo $cookie; ?>" >
            <?php echo $i ?>
          </a>
  <?php } ?>
        </li>
<?php } ?>

<?php if(count($all_items) === $item_last_child_num || $item_last_child_num == 0){?>
        <li class = "page-item disabled">
        <span class="page-link">次へ</span>
        </li>
<?php }else{ ?>
        <li class = "page-item">
          <a class="page-link" href = "<?php echo next_page_button($page_num)?><?php echo $cookie; ?>">次へ</a>
        </li>
<?php } ?>
      </ul>
  </nav>
  <?php var_dump($count_item_num); ?>