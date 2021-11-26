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

// フォルダ一覧取得
function getFolders($user_id)
{

    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title FROM folders WHERE user_id = :user_id AND is_deleted = 0';
        $data = array(':user_id' => $user_id);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}