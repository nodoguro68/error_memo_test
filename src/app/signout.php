<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../common/auth.php';

if (!empty($_POST)) {
    $user_id = $_SESSION['user_id'];
    $mail_address = filter_input(INPUT_POST, 'mail_address');
    $password = filter_input(INPUT_POST, 'password');

    validRequired($mail_address, 'mail_address');
    validRequired($password, 'password');

    if (empty($err_msg)) {

        validMaxLen($mail_address, 'mail_address');
        validMail($mail_address);

        validPass($password, 'password');

        if (empty($err_msg)) {

            $db_data = getPassword($user_id);
            if (password_verify($password, $db_data['password'])) {

                if (signout($user_id)) {
                    header('Location: index.php');
                }
            } else {
                $err_msg['common'] = ERR_MSG_AUTH;
            }
        }
    }
}

$page_title = '退会';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <div class="">
            <a href="mypage.php" class="">戻る</a>
        </div>

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">退会</h2>
                <p class="form__description">
                    退会するにはメールアドレスとパスワードが必要です。
                    退会すると全てのデータが削除されます。
                </p>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item">
                    <label for="mail_address" class="form__label">メールアドレス</label>
                    <input type="text" name="mail_address" class="form__input" id="mail_address" value="<?= getFormData('mail_address'); ?>">
                    <span class="err-msg"><?= getErrMsg('mail_address'); ?></span>
                </div>
                <div class="form__item">
                    <label for="password" class="form__label">パスワード</label>
                    <input type="password" name="password" class="form__input" id="password" value="<?= getFormData('password'); ?>">
                    <span class="err-msg"><?= getErrMsg('password'); ?></span>
                </div>
            </div>
            <div class="form__footer">
                <div class="">
                    <input type="submit" value="退会" class="btn">
                </div>
            </div>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>