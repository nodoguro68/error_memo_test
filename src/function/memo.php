<?php

// メモ登録
function createMemo($memo_data)
{
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO memos (user_id, folder_id, category_id, title, ideal, solution, attempt, reference, etc, created_at, is_solved , is_published) VALUES(:user_id, :folder_id, :category_id, :title, :ideal, :solution, :attempt, :reference, :etc, :created_at, :is_solved, :is_published)';
        $data = array(
            ':user_id' => $memo_data['user_id'],
            ':folder_id' => $memo_data['folder_id'],
            ':category_id' => $memo_data['category_id'],
            ':title' => $memo_data['title'],
            ':ideal' => $memo_data['ideal'],
            ':solution' => $memo_data['solution'],
            ':attempt' => $memo_data['attempt'],
            ':reference' => $memo_data['reference'],
            ':etc' => $memo_data['etc'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':is_solved' => $memo_data['is_solved'],
            ':is_published' => $memo_data['is_published'],
        );

        if (queryPost($dbh, $sql, $data)) {

            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// 自分のメモ取得
function getMyMemo($memo_id, $user_id)
{
    try {

        $dbh = dbConnect();
        $sql = 'SELECT memo_id, m.category_id, m.title, ideal, solution, attempt, reference, etc, m.created_at, is_solved, is_published, c.title AS category_title FROM memos AS m INNER JOIN categories AS c ON m.category_id = c.category_id WHERE memo_id = :memo_id AND m.user_id = :user_id AND m.is_deleted = 0';
        $data = array(
            ':memo_id' => $memo_id,
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// 全てのメモ取得
function getMemos()
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title FROM memos WHERE is_published = 1 AND is_deleted = 0';
        $stmt = $dbh->query($sql);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// フォルダ内のメモ取得
function getMemoInFolder($user_id, $folder_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title, is_published FROM memos WHERE user_id = :user_id AND folder_id = :folder_id AND is_deleted = 0';
        $data = array(
            ':user_id' => $user_id,
            ':folder_id' => $folder_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// 未解決のメモ取得
function getUnsolvedMemos($user_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title FROM memos WHERE user_id = :user_id AND is_solved = 0 AND is_deleted = 0';
        $data = array(
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// 未解決のメモ取得
function getSolvedMemos($user_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title, is_published FROM memos WHERE user_id = :user_id AND is_solved = 1 AND is_deleted = 0';
        $data = array(
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
