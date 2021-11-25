<?php

// エラーログ
ini_set('log_errors', 'on');
ini_set('error_log', '../../php.log');

// デバッグ
$debug_flag = true;
function debug($str)
{
    global $debug_flag;
    if (!empty($debug_flag)) {
        error_log('デバッグ：' . $str);
    }
}

// 画面表示処理開始ログ吐き出し関数
function debugLogStart()
{
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
    debug('セッションID：' . session_id());
    debug('セッション変数の中身：' . print_r($_SESSION, true));
    debug('現在日時タイムスタンプ：' . time());
    if (!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])) {
        debug('ログイン期限日時タイムスタンプ：' . ($_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

// セッション
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime', 60 * 60 * 24 * 30);
ini_set('session.cookie_lifetime ', 60 * 60 * 24 * 30);
session_start();
session_regenerate_id();


// エラーメッセージ配列
$err_msg = array();

// DB接続
function dbConnect()
{

    try {
        $dsn = 'mysql:dbname=error_memo;host=127.0.0.1;charset=utf8';
        $user = 'root';
        $password = 'root';
        $options = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        $dbh = new PDO($dsn, $user, $password, $options);
        return $dbh;
    } catch (PDOException $e) {
        exit('エラー' . $e->getMessage());
    }
}

// プリペアードステートメント
function queryPost($dbh, $sql, $data)
{
    $stmt = $dbh->prepare($sql);

    if (!$stmt->execute($data)) {
        debug('クエリに失敗しました。');
        debug('失敗したSQL：' . print_r($stmt, true));
        $err_msg['common'] = ERR_MSG;
        return false;
    }
    debug('クエリ成功。');
    return $stmt;
}

// サニタイズ
function sanitize($str)
{
    return htmlspecialchars($str, ENT_QUOTES);
}

// フォーム入力保持
function getFormData($str, $flag = false)
{
    if ($flag) {
        $method = $_GET;
    } else {
        $method = $_POST;
    }
    global $db_form_data;
    if (!empty($db_form_data)) {
        if (!empty($err_msg[$str])) {
            if (isset($method[$str])) {
                return sanitize($method[$str]);
            } else {
                return sanitize($db_form_data[$str]);
            }
        } else {
            if (isset($method[$str]) && $method[$str] !== $db_form_data[$str]) {
                return sanitize($method[$str]);
            } else {
                return sanitize($db_form_data[$str]);
            }
        }
    } else {
        if (isset($method[$str])) {
            return sanitize($method[$str]);
        }
    }
}

//エラーメッセージ表示
function getErrMsg($key)
{
    global $err_msg;
    if (!empty($err_msg[$key])) {
        return $err_msg[$key];
    }
}
