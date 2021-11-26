<?php

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
