<?php

// メモ登録
function createMemo($memo_data)
{
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO memos (user_id, folder_id, category_id, title, ideal, attempt, solution, cause, reference, etc, created_at, is_solved , is_published) VALUES(:user_id, :folder_id, :category_id, :title, :ideal, :attempt, :solution, :cause, :reference, :etc, :created_at, :is_solved, :is_published)';
        $data = array(
            ':user_id' => $memo_data['user_id'],
            ':folder_id' => $memo_data['folder_id'],
            ':category_id' => $memo_data['category_id'],
            ':title' => $memo_data['title'],
            ':ideal' => $memo_data['ideal'],
            ':attempt' => $memo_data['attempt'],
            ':solution' => $memo_data['solution'],
            ':solution' => $memo_data['cause'],
            ':reference' => $memo_data['reference'],
            ':etc' => $memo_data['etc'],
            ':created_at' => date('Y-m-d H:i:s'),
            ':is_solved' => $memo_data['is_solved'],
            ':is_published' => $memo_data['is_published'],
        );

        if (queryPost($dbh, $sql, $data)) {
            $_SESSION['suc_msg'] = SUC_MSG_MEMO_SAVE;
            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// メモ編集
function editMemo($memo_data)
{
    try {
        $dbh = dbConnect();
        $sql = 'UPDATE memos SET category_id = :category_id, title = :title, ideal = :ideal, attempt = :attempt, solution = :solution, cause = :cause, reference = :reference, etc = :etc, is_solved = :is_solved, is_published = :is_published  WHERE id = :memo_id AND user_id = :user_id AND is_deleted = 0';
        $data = array(
            ':memo_id' => $memo_data['id'],
            ':user_id' => $memo_data['user_id'],
            ':category_id' => $memo_data['category_id'],
            ':title' => $memo_data['title'],
            ':ideal' => $memo_data['ideal'],
            ':attempt' => $memo_data['attempt'],
            ':solution' => $memo_data['solution'],
            ':cause' => $memo_data['cause'],
            ':reference' => $memo_data['reference'],
            ':etc' => $memo_data['etc'],
            ':is_solved' => $memo_data['is_solved'],
            ':is_published' => $memo_data['is_published'],
        );

        if (queryPost($dbh, $sql, $data)) {
            $_SESSION['suc_msg'] = SUC_MSG_MEMO_SAVE;
            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// メモ削除
function deleteMemo($memo_id)
{
    try {
        $dbh = dbConnect();
        $sql = 'DELETE FROM memos WHERE id = :memo_id AND is_deleted = 0';
        $data = array(
            ':memo_id' => $memo_id,
        );

        if (queryPost($dbh, $sql, $data)) {
            $_SESSION['suc_msg'] = SUC_MSG_MEMO_DELETE;
            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// メモ削除
function deleteMemosInFolder($folder_id)
{
    try {
        $dbh = dbConnect();
        $sql = 'DELETE FROM memos WHERE folder_id = :folder_id AND is_deleted = 0';
        $data = array(
            ':folder_id' => $folder_id,
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
        $sql = 'SELECT m.id, category_id, m.title, ideal, attempt, solution, cause, reference, etc, m.created_at, is_solved, is_published, c.title AS category_title FROM memos AS m INNER JOIN categories AS c ON m.category_id = c.id WHERE m.id = :memo_id AND user_id = :user_id AND m.is_deleted = 0';
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

// メモ詳細取得
function getMemo($memo_id)
{
    try {

        $dbh = dbConnect();
        $sql = 'SELECT m.id AS id, user_id, m.title, ideal, attempt, solution, cause, reference, etc, m.created_at, is_solved, is_published, c.title AS category_title, user_name, profile_img
        FROM memos AS m 
        INNER JOIN categories AS c 
        ON m.category_id = c.id 
        INNER JOIN users AS u
        ON m.user_id = u.id
        WHERE m.id = :memo_id AND m.is_published = 1 AND m.is_deleted = 0';
        $data = array(
            ':memo_id' => $memo_id,
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
function getMemos($current_min_num = 1, $per_page = 20)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id FROM memos WHERE is_published = 1 AND is_deleted = 0';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);

        $result['total'] = $stmt->rowCount();
        $result['total_page'] =
        ceil($result['total'] / $per_page);

        $sql = 'SELECT m.id, user_id, title, m.created_at AS created_at, user_name 
        FROM memos AS m
        INNER JOIN users AS u
        ON m.user_id = u.id 
        WHERE is_published = 1 AND m.is_deleted = 0
        LIMIT :per_page OFFSET :current_min_num';
        $data = array(
            ':current_min_num' => $current_min_num,
            ':per_page' => $per_page,
        );
        $stmt = queryPost($dbh, $sql, $data);

        $result['memo'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $result['count'] = $stmt->rowCount();

        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        // $err_msg['common'] = ERR_MSG;
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
        $result = array(
            'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'count' => $stmt->rowCount()
        );
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
        $result = array(
            'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'count' => $stmt->rowCount()
        );
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
        $result = array(
            'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'count' => $stmt->rowCount()
        );
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// お気に入りのメモを全て取得
function getFavoriteMemos($user_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT m.id AS id, m.user_id AS user_id, title, m.created_at AS created_at, user_name
        FROM favorite_memos AS fm
        INNER JOIN memos AS m
        ON fm.memo_id = m.id
        INNER JOIN users AS u
        ON m.user_id = u.id
        WHERE fm.user_id = :user_id';
        $data = array(
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = array(
            'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'count' => $stmt->rowCount()
        );
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// ユーザーのメモ全て取得
function getUsersMemos($user_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title, created_at FROM memos WHERE user_id = :user_id AND is_deleted = 0';
        $data = array(
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = array(
            'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'count' => $stmt->rowCount()
        );
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// いいねされているメモかチェック
function checkFavoriteMemo($memo_id, $user_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT memo_id FROM favorite_memos WHERE memo_id = :memo_id AND user_id = :user_id';
        $data = array(
            ':memo_id' => $memo_id,
            ':user_id' => $user_id,
        );

        $stmt = queryPost($dbh, $sql, $data);
        $resultCount = $stmt->rowCount();
        return $resultCount;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// お気に入りメモを新規登録
function createFavoriteMemo($memo_id, $user_id)
{

    try {

        $dbh = dbConnect();

        $sql = 'INSERT INTO favorite_memos (memo_id, user_id, created_at) VALUES (:memo_id, :user_id, :created_at)';
        $data = array(
            ':memo_id' => $memo_id,
            ':user_id' => $user_id,
            ':created_at' => date('Y-m-d H:i:s')
        );

        if (queryPost($dbh, $sql, $data)) {

            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// お気に入りメモを削除
function deleteFavoriteMemo($memo_id, $user_id)
{

    try {

        $dbh = dbConnect();

        $sql = 'DELETE FROM favorite_memos WHERE memo_id = :memo_id AND user_id = :user_id';
        $data = array(
            ':memo_id' => $memo_id,
            ':user_id' => $user_id,
        );

        if (queryPost($dbh, $sql, $data)) {
            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// いいねされているかを確認し、されていなければactiveクラスをつける
function isFavoriteMemo($memo_id, $user_id)
{

    try {
        $dbh = dbConnect();
        $sql = 'SELECT memo_id FROM favorite_memos WHERE memo_id = :memo_id AND user_id = :user_id';
        $data = array(':memo_id' => $memo_id, ':user_id' => $user_id);
        $stmt = queryPost($dbh, $sql, $data);

        if ($stmt->rowCount()) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
}


// メモ検索
function searchMemo($q, $category_id, $sort)
{
    global $err_msg;
    if($q) {
        if($sort === 'new' || $sort === '0') {
            $sort = 'DESC';
            
        } elseif($sort === 'old'){
            $sort = 'ASC';
    
        }
        try {
    
            $dbh = dbConnect();
    
            if ($category_id != '0') {
                $sql = "SELECT id, user_id, title, created_at 
                FROM memos 
                WHERE category_id = :category_id AND title LIKE '%" . $q . "%' AND is_published = 1 AND is_deleted = 0 ORDER BY created_at $sort";
                $data = array(
                    ':category_id' => $category_id,
                );
                $stmt = queryPost($dbh, $sql, $data);
    
            } else {
                $sql = "SELECT id, user_id, title, created_at 
                FROM memos 
                WHERE title LIKE '%" . $q . "%' AND is_published = 1 AND is_deleted = 0 ORDER BY created_at $sort";
                $stmt = $dbh->query($sql);
            }
    
            $result = array(
                'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'count' => $stmt->rowCount()
            );
            return $result;
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = ERR_MSG;
        }
    } else {
        $result = array(
            'memo' => null,
            'count' => null
        );
    }
}

// 自分のメモ検索
function searchMyMemo($user_id, $q, $category_id, $sort)
{
    global $err_msg;
    if($q) {
        if ($sort === 'new' || $sort === '0') {
            $sort = 'DESC';
        } elseif ($sort === 'old') {
            $sort = 'ASC';
        }
        
        try {

            $dbh = dbConnect();

            if($category_id !== '0') {
                $sql = "SELECT id, user_id, title, created_at, is_solved
                FROM memos 
                WHERE user_id = :user_id AND category_id = :category_id AND title LIKE '%" . $q . "%' AND is_deleted = 0 ORDER BY created_at $sort";
                $data = array(
                    ':user_id' => $user_id,
                    ':category_id' => $category_id,
                );
            } else {
                $sql = "SELECT id, user_id, title, created_at 
                FROM memos 
                WHERE user_id = :user_id AND title LIKE '%" . $q . "%' AND is_published = 1 AND is_deleted = 0 ORDER BY created_at $sort";
                $data = array(
                    ':user_id' => $user_id,
                );
            }

            $stmt = queryPost($dbh, $sql, $data);
            $result = array(
                'memo' => $stmt->fetchAll(PDO::FETCH_ASSOC),
                'count' => $stmt->rowCount()
            );
            return $result;
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = ERR_MSG;
        }
    } else {
        $result = array(
            'memo' => null,
            'count' => null
        );
    }
}


// いいね件数取得
function countFavoriteMemo($memo_id)
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT count(*) FROM favorite_memos WHERE memo_id = :memo_id AND is_deleted = 0';
        $data = array(
            ':memo_id' => $memo_id
        );

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchColumn();
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}