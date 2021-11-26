<?php

// ユーザー登録
function createUser($user_name, $mail_address, $password)
{
    try {
        $dbh = dbConnect();
        $sql = 'INSERT INTO users (user_name, mail_address, password, login_time, created_at) VALUES(:user_name, :mail_address, :password, :login_time, :created_at)';
        $data = array(
            ':user_name' => $user_name,
            ':mail_address' => $mail_address,
            ':password' => password_hash($password, PASSWORD_DEFAULT),
            ':login_time' => date('Y-m-d H:i:s'),
            ':created_at' => date('Y-m-d H:i:s'),
        );

        if (queryPost($dbh, $sql, $data)) {

            $session_limit = 60 * 60;
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $session_limit;
            $_SESSION['user_id'] = $dbh->lastInsertId();
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// メールアドレスでユーザーのidとパスワードを取得
function getUserDataByMailAddress($mail_address)
{

    try {

        $dbh = dbConnect();

        $sql = 'SELECT id, password FROM users WHERE mail_address = :mail_address AND is_deleted = 0';
        $data = array(':mail_address' => $mail_address);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// ログイン
function login($mail_address, $password, $pass_save)
{

    $user_data = getUserDataByMailAddress($mail_address);

    if (!empty($user_data) && password_verify($password, $user_data['password'])) {

        $sesLimit = 60 * 60;
        $_SESSION['login_date'] = time();

        if ($pass_save) {
            $_SESSION['login_limit'] = $sesLimit * 24 * 30;
        } else {
            $_SESSION['login_limit'] = $sesLimit;
        }
        $_SESSION['user_id'] = $user_data['id'];

        return true;
    } else {
        $err_msg['common'] = ERR_MSG_LOGIN;
    }
}

// パスワード取得
function getPassword($user_id)
{
    try {

        $dbh = dbConnect();
        $sql = 'SELECT password FROM users WHERE id = :id AND is_deleted = 0';
        $data = array(':id' => $user_id);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// パスワード更新
function updatePass($user_id, $new_password)
{
    try {

        $dbh = dbConnect();
        $sql = 'UPDATE users SET password = :password WHERE id = :id AND is_deleted = 0';
        $data = array(
            ':id' => $user_id,
            ':password' => password_hash($new_password, PASSWORD_DEFAULT),
        );

        if (queryPost($dbh, $sql, $data)) {

            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
