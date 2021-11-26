<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../common/auth.php';


if (!empty($_POST)) {
    $user_id = $_SESSION['user_id'];
    $old_password = filter_input(INPUT_POST, 'old_password');
    $new_password = filter_input(INPUT_POST, 'new_password');
    $new_password_re = filter_input(INPUT_POST, 'new_password_re');

    validRequired($old_password, 'old_password');
    validRequired($new_password, 'new_password');
    validRequired($new_password_re, 'new_password_re');

    if (empty($err_msg)) {

        validPass($old_password, 'old_password');
        validPass($new_password, 'new_password');
        validPassVerify($user_id, $old_password, 'old_password');
        validNewPass($old_password, $new_password);
        validMatch($new_password, $new_password_re, 'new_password');

        if (empty($err_msg) && updatePass($user_id, $new_password)) {

            header('Location: mypage.php');
        }
    }
}

$page_title = 'パスワード変更';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">パスワード変更</h2>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item">
                    <label for="old_password" class="form__label">古いパスワード</label>
                    <input type="password" name="old_password" class="form__input" id="old_password" value="<?= getFormData('old_password'); ?>">
                    <span class="err-msg"><?= getErrMsg('old_password'); ?></span>
                </div>
                <div class="form__item">
                    <label for="new_password" class="form__label">新しいパスワード<span class="form__note">半角英数字8文字以上</span></label>
                    <input type="password" name="new_password" class="form__input" id="new_password" value="<?= getFormData('new_password'); ?>">
                    <span class="err-msg"><?= getErrMsg('new_password'); ?></span>
                </div>
                <div class="form__item">
                    <label for="new_password_re" class="form__label">新しいパスワード（確認）</label>
                    <input type="password" name="new_password_re" class="form__input" id="new_password_re" value="<?= getFormData('new_password_re'); ?>">
                    <span class="err-msg"><?= getErrMsg('new_password_re'); ?></span>
                </div>
            </div>
            <div class="form__footer">
                <div class="">
                    <input type="submit" value="保存" class="btn">
                </div>
            </div>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>