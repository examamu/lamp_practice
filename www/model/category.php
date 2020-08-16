<?php
    require_once MODEL_PATH . 'functions.php';
    require_once MODEL_PATH . 'db.php';

    function get_all_categories($db){
        $sql = "
            SELECT
            id,
            name
            FROM
            categories
        ";
        return fetch_all_query($db, $sql);
    }
?>