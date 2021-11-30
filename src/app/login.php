<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../common/auth.php';

if (!empty($_POST)) {
    $mail_address = filter_input(INPUT_POST, 'mail_address');
    $password = filter_input(INPUT_POST, 'password');
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    validRequired($mail_address, 'mail_address');
    validRequired($password, 'password');

    if (empty($err_msg)) {

        validMaxLen($mail_address, 'mail_address');
        validMail($mail_address);

        validPass($password, 'password');

        if (empty($err_msg) && login($mail_address, $password, $pass_save)) {
            header('Location: mypage.php');
        }
    }
}

$page_title = 'ログイン';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">ログイン</h2>
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
                    <input type="password" name="password" class="form__input" id="password" value="<?= getFormData('password'); ?>">
                    <span class="err-msg"><?= getErrMsg('password'); ?></span>
                </div>
            </div>
            <div class="form__footer">
                <label>
                    <input type="checkbox" name="pass_save">自動でログイン
                </label>
                <div class="">
                    <input type="submit" value="ログイン" class="btn">
                </div>
                <div class="link-container">
                    <a href="pass_reissue_send.php" class="form__link">パスワードを忘れた場合はこちら</a>
                    <a href="signup.php" class="form__link">新規登録はこちら</a>
                </div>
            </div>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>