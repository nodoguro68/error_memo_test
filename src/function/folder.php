<?php 

// フォルダ新規登録
function createFolder($user_id, $folder)
{
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO folders (user_id, title, created_at) VALUES(:user_id, :title, :created_at)';

        $data = array(
            ':user_id' => $user_id,
            ':title' => $folder,
            ':created_at' => date('Y-m-d H:i:s'),
        );

        if (queryPost($dbh, $sql, $data)) {
            return;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}