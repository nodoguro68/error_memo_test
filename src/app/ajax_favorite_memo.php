<?php

require_once '../common/common.php';
require_once '../function/memo.php';

if (isset($_POST['memoId']) && isset($_SESSION['user_id'])) {

    $memo_id = filter_input(INPUT_POST, 'memoId');
    $user_id = $_SESSION['user_id'];

    try {

        $result_count = checkFavoriteMemo($memo_id, $user_id);

        if (!empty($result_count)) {

            deleteFavoriteMemo($memo_id, $user_id);

        } else {

            createFavoriteMemo($memo_id, $user_id);
            
        }
        
        echo $memo_count = countFavoriteMemo($memo_id);

    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
