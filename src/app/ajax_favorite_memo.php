<?php

require_once '../common/common.php';
require_once '../function/memo.php';

if (isset($_POST['memoId']) && isset($_SESSION['user_id'])) {

    $memo_id = filter_input(INPUT_POST, 'memoId');
    $user_id = $_SESSION['user_id'];

    try {

        $resultCount = checkFavoriteMemo($memo_id, $user_id);

        if (!empty($resultCount)) {

            deleteFavoriteMemo($memo_id, $user_id);

        } else {

            createFavoriteMemo($memo_id, $user_id);
            
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
