<div class = "sidebar">
    <div class = "sidebar_search_box">
        <h2>キーワード検索</h2>
        <form method = "GET">
            <input type ="text" name = "search_word" placeholder = "キーワードを入力してください">
            <input type = "submit" value = "検索">
        </form>
    </div>

<nav id="side_nav">
    <div class="side_nav_inner">
        <h2>カテゴリーから検索</h2>
        <ul>
<?php foreach( $categories as $category){ ?>
            <li><a href="?category=<?php echo $category['id'] ?>"><?php echo $category['name'] ?></a></li>
<?php } ?>
        </ul>
    </div> 
</nav>

</div>