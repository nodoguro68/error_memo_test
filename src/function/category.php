<?php 

// 全てのカテゴリー取得
function getCategories()
{
    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, title FROM categories WHERE is_deleted = 0';
        $stmt = $dbh->query($sql);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}