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
    global $err_msg;

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
        $err_msg['common'] = ERR_MSG_AUTH;
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

// プロフィール取得
function getProfileData($user_id)
{
    try {

        $dbh = dbConnect();
        $sql = 'SELECT user_name, description, mail_address, profile_img, github, facebook, twitter FROM users WHERE id = :id AND is_deleted = 0';
        $data = array(':id' => $user_id);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// プロフィール更新
function updateProfile($user_id, $user_name, $description, $mail_address, $profile_img, $github, $facebook, $twitter)
{
    try {

        $dbh = dbConnect();
        $sql = 'UPDATE users SET user_name = :user_name, description = :description, mail_address = :mail_address, profile_img = :profile_img, github = :github, facebook = :facebook, twitter = :twitter WHERE id = :id AND is_deleted = 0';
        $data = array(
            ':id' => $user_id,
            ':user_name' => $user_name,
            ':description' => $description,
            ':mail_address' => $mail_address,
            ':profile_img' => $profile_img,
            ':github' => $github,
            ':facebook' => $facebook,
            ':twitter' => $twitter,
        );

        if (queryPost($dbh, $sql, $data)) {

            return true;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}


// ユーザー情報取得
function getUserInfo($user_id)
{
    try {

        $dbh = dbConnect();
        $sql = 'SELECT user_name, description, profile_img FROM users WHERE id = :user_id AND is_deleted = 0';
        $data = array(':user_id' => $user_id);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// 退会
function signout($user_id)
{
    try {

        $dbh = dbConnect();

        $sql = array(
            'folders' => 'UPDATE folders SET is_deleted = 1 WHERE user_id = :user_id AND is_deleted = 0',
            'memos' => 'UPDATE memos SET is_deleted = 1 WHERE user_id = :user_id AND is_deleted = 0',
            'favorite_memos' => 'UPDATE favorite_memos SET is_deleted = 1 WHERE user_id = :user_id AND is_deleted = 0',
            'users' => 'UPDATE users SET is_deleted = 1 WHERE id = :user_id AND is_deleted = 0',
        );
        $data = array(
            ':user_id' => $user_id,
        );

        $stmt1 = queryPost($dbh, $sql['folders'], $data);
        $stmt2 = queryPost($dbh, $sql['memos'], $data);
        $stmt3 = queryPost($dbh, $sql['favorite_memos'], $data);
        $stmt4 = queryPost($dbh, $sql['users'], $data);

        if ($stmt1 && $stmt2 && $stmt3 && $stmt4) {
            $_SESSION = array();
            session_destroy();
            return true;
        }
    } catch (Exception $e) {

        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}
