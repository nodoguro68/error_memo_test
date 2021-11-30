<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../function/user.php';

if (!empty($_POST)) {

    $mail_address = filter_input(INPUT_POST, 'mail_address');

    validRequired($mail_address, 'mail_address');

    if (empty($err_msg)) {

        validMaxLen($mail_address, 'mail_address');
        validMail($mail_address);

        if (empty($err_msg)) {

            $result = checkMailAddress($mail_address);
            if (array_shift($result)) {
                $auth_key = createRandomKey();

                // メール送信

                $_SESSION['mail_address'] = $mail_address;
                $_SESSION['auth_key'] = $auth_key;
                $_SESSION['auth_key_limit'] = time() + (60 * 30);

                header('Location: pass_reissue_recieve.php');
            } else {
                $err_msg['common'] = ERR_MSG;
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

        <div class=""><a href="login.php" class="">＞ 戻る</a></div>

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title">パスワード再発行</h2>
                <p class="form__description">入力されたメールアドレス宛にパスワード再発行用のURLと認証キーをお送り致します。</p>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item">
                    <label for="mail_address" class="form__label">メールアドレス</label>
                    <input type="text" name="mail_address" class="form__input" id="mail_address" value="<?= getFormData('mail_address'); ?>">
                    <span class="err-msg"><?= getErrMsg('mail_address'); ?></span>
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