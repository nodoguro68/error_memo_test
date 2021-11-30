<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';

if (empty($_SESSION['auth_key'])) {
    header('Location: pass_reissue_send.php');
}

if (!empty($_POST)) {

    $auth_key = filter_input(INPUT_POST, 'auth_key');

    validRequired($auth_key, 'auth_key');

    if (empty($err_msg)) {

        validMaxLen($auth_key, 'auth_key');
        validMinLen($auth_key, 'auth_key');
        validHalf($auth_key, 'auth_key');

        if (empty($err_msg)) {

            if ($auth_key !== $_SESSION['auth_key']) {
                $err_msg['auth_key'] = ERR_MSG_INCORRECT;
            }
            if (time() > $_SESSION['auth_key_limit']) {
                $err_msg['auth_key'] = ERR_MSG_EXPIRE;
            }

            if (empty($err_msg)) {

                $password = createRandomKey();

                if (reissuePass($_SESSION['mail_address'], $password)) {

                    // メール送信

                    session_unset();
                    print_r($_SESSION);
                    header('Location: login.php');
                }
            }
        }
    }
}

$page_title = 'パスワード再発行';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <div class=""><a href="pass_reissue_send.php" class="">＞ 戻る</a></div>

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">パスワード再発行</h2>
                <p class="form__description"></p>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item">
                    <label for="auth_key" class="form__label">認証キー</label>
                    <input type="text" name="auth_key" class="form__input" id="auth_key" value="<?= getFormData('auth_key'); ?>">
                    <span class="err-msg"><?= getErrMsg('auth_key'); ?></span>
                </div>
            </div>
            <div class="form__footer">
                <div class="">
                    <input type="submit" value="送信" class="btn">
                </div>
            </div>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>