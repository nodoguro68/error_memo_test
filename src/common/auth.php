<?php

// ログインしている場合
if (!empty($_SESSION['login_date'])) {

    // 現在日時がログイン日時とログイン期限を足したものを超えていた場合
    if ($_SESSION['login_date'] + $_SESSION['login_limit'] < time()) {

        session_destroy();
        header('Location: login.php');

    } else { // ログイン期限内の場合

        $_SESSION['login_date'] = time();

        if (basename($_SERVER['PHP_SELF']) === 'login.php') {
            header('Location: mypage.php');
        }
    }

} else { // ログインしていない場合
    
    if (basename($_SERVER['PHP_SELF']) !== 'login.php') {
        header('Location: login.php');
    }
}
