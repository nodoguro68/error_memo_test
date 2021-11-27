<?php

require_once 'message.php';

// 入力チェック
function validRequired($str, $key)
{
    if ($str === '') {
        global $err_msg;
        $err_msg[$key] = ERR_MSG_REQUIRED;
    }
}

// 最大文字数チェック
function validMaxLen($str, $key, $max = 256)
{
    if (mb_strlen($str) > $max) {
        global $err_msg;
        $err_msg[$key] = $max . ERR_MSG_MAX_LEN;
    }
}

// 最小文字数チェック
function validMinLen($str, $key, $min = 8)
{
    if (mb_strlen($str) < $min) {
        global $err_msg;
        $err_msg[$key] = $min . ERR_MSG_MIN_LEN;
    }
}

// ユーザーネーム重複チェック
function validUserNameDup($user_name)
{

    global $err_msg;

    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE user_name = :user_name AND is_deleted = 0';
        $data = array(':user_name' => $user_name);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty(array_shift($result))) {
            $err_msg['user_name'] = ERR_MSG_DUP;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// メールアドレス形式チェック
function validMail($str)
{
    if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $str)) {
        global $err_msg;
        $err_msg['mail_address'] = ERR_MSG_EMAIL;
    }
}

// メールアドレス重複チェック
function validMailDup($mail_address)
{

    global $err_msg;

    try {
        $dbh = dbConnect();
        $sql = 'SELECT count(*) FROM users WHERE mail_address = :mail_address AND is_deleted = 0';
        $data = array(':mail_address' => $mail_address);

        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!empty(array_shift($result))) {
            $err_msg['mail_address'] = ERR_MSG_DUP;
        }
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
        $err_msg['common'] = ERR_MSG;
    }
}

// 半角チェック
function validHalf($str, $key)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = ERR_MSG_HALF;
    }
}

// パスワード再入力チェック
function validMatch($str1, $str2, $key)
{
    if ($str1 !== $str2) {
        global $err_msg;
        $err_msg[$key] = ERR_MSG_PASS_RE;
    }
}

// パスワードバリデーション
function validPass($str, $key)
{
    validHalf($str, $key);
    validMaxLen($str, $key);
    validMinLen($str, $key);
}

// 古いパスワード認証
function validPassVerify($user_id, $old_pass, $key)
{
    $user_data = getPassword($user_id);
    if (!password_verify($old_pass, $user_data['password'])) {
        global $err_msg;
        $err_msg[$key] = ERR_MSG_OLD_PASS;
    }
}

// 古いパスワードと新しいパスワードが同じかチェック
function validNewPass($old_pass, $new_pass)
{
    if ($old_pass === $new_pass) {
        global $err_msg;
        $err_msg['new_password'] = ERR_MSG_NEW_PASS;
    }
}

// フォルダ重複チェック
function validFolderDup($user_id, $folder)
{
    global $err_msg;

    $dbh = dbConnect();
    $sql = 'SELECT count(*) FROM folders WHERE user_id = :user_id AND title = :title AND is_deleted = 0';
    $data = array(
        ':title' => $folder,
        ':user_id' => $user_id
    );

    $stmt = queryPost($dbh, $sql, $data);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty(array_shift($result))) {
        $err_msg['create_folder'] = ERR_MSG_DUP;
    }
}

// 解決方法が入力された状態で解決済みにしているかどうか
function validSolved($solution, $is_solved)
{
    if (empty($solution) && $is_solved === '1') {
        global $err_msg;
        $err_msg['is_solved'] = ERR_MSG_SOLVED;
    }
}

// 解決済みにした状態で公開を選択しているかどうか
function validPublished($is_published, $is_solved)
{
    if ($is_published === '1' && $is_solved === '0') {
        global $err_msg;
        $err_msg['is_published'] = ERR_MSG_PUBLISH;
    }
}