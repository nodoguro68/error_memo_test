<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';


if (!empty($_POST)) {
    $user_name = uniqid();
    $mail_address = filter_input(INPUT_POST, 'mail_address');
    $password = filter_input(INPUT_POST, 'password');
    $password_re = filter_input(INPUT_POST, 'password_re');

    validRequired($mail_address, 'mail_address');
    validRequired($password, 'password');
    validRequired($password_re, 'password_re');

    if (empty($err_msg)) {

        validUserNameDup($user_name);

        validMaxLen($mail_address, 'mail_address');
        // validEmail($mail_address);
        validEmailDup($mail_address);

        validMatch($password, $password_re, 'password');
        validPass($password, 'password');

        if (empty($err_msg)) {

            createUser($user_name, $mail_address, $password);
            header('Location: mypage.php');
        }
    }
}

$page_title = 'ユーザー登録';
require_once '../template/head.php';
require_once '../template/header.php';
?>

<main class="main">
    <div class="container">

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">ユーザー登録</h2>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item">
                    <label for="mail_address" class="form__label">メールアドレス</label>
                    <input type="text" name="mail_address" class="form__input" id="mail_address" value="<?= getFormData('mail_address'); ?>">
                    <span class="err-msg"><?= getErrMsg('mail_address'); ?></span>
                </div>
                <div class="form__item">
                    <label for="password" class="form__label">パスワード<span class="form__note">半角英数字8文字以上</span></label>
                    <input type="text" name="password" class="form__input" id="password" value="<?= getFormData('password'); ?>">
                    <span class="err-msg"><?= getErrMsg('password'); ?></span>
                </div>
                <div class="form__item">
                    <label for="password_re" class="form__label">パスワード（確認）</label>
                    <input type="text" name="password_re" class="form__input" id="password_re" value="<?= getFormData('password_re'); ?>">
                    <span class="err-msg"><?= getErrMsg('password_re'); ?></span>
                </div>
            </div>
            <div class="form__footer">
                <div class="">
                    <input type="submit" value="登録" class="btn">
                </div>
            </div>
        </form>
    </div>

</main>
</body>

</html>