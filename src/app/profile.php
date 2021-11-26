<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../common/upload.php';
require_once '../common/validation.php';
require_once '../function/user.php';

$user_id = $_SESSION['user_id'];
$db_form_data = getProfileData($user_id);

if (!empty($_POST)) {
    $profile_img = (!empty($_FILES['profile_img']['name'])) ? uploadImg($_FILES['profile_img'], 'profile_img') : '';
    $profile_img = (empty($profile_img) && !empty($db_form_data['profile_img'])) ? $db_form_data['profile_img'] : $profile_img;
    $user_name = filter_input(INPUT_POST, 'user_name');
    $description = filter_input(INPUT_POST, 'description');
    $mail_address = filter_input(INPUT_POST, 'mail_address');
    $github = filter_input(INPUT_POST, 'github');
    $facebook = filter_input(INPUT_POST, 'facebook');
    $twitter = filter_input(INPUT_POST, 'twitter');


    if ($db_form_data['user_name'] !== $user_name) {
        validRequired($user_name, 'user_name');
        validMaxLen($user_name, 'user_name');
        validUserNameDup($user_name);
    }

    if ($db_form_data['description'] !== $description) {
        validMaxLen($description, 'description', 100);
    }

    if ($db_form_data['mail_address'] !== $mail_address) {
        validRequired($mail_address, 'mail_address');
        validMaxLen($mail_address, 'mail_address');
        validMail($mail_address);
        validMailDup($mail_address);
    }
    if ($db_form_data['github'] !== $github) {
        validMaxLen($github, 'github');
    }
    if ($db_form_data['facebook'] !== $facebook) {
        validMaxLen($facebook, 'facebook');
    }
    if ($db_form_data['twitter'] !== $twitter) {
        validMaxLen($twitter, 'twitter');
    }

    if (empty($err_msg) && updateProfile($user_id, $user_name, $description, $mail_address, $profile_img, $github, $facebook, $twitter)) {

        header('Location: mypage.php');
    }
}

$page_title = 'プロフィール';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <form method="post" class="form" enctype="multipart/form-data">
            <div class="form__header">
                <h2 class="form__title">プロフィール</h2>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <div class="form__item-img">
                    <input type="file" name="profile_img" class="form__file">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <img src="<?= (!empty($db_form_data['profile_img']) ? getFormData('profile_img', $db_form_data) : '../resource/img/no-image.png'); ?>" alt="プロフィール画像" class="">
                </div>
                <div class="form__item">
                    <label for="user_name" class="form__label">ユーザーネーム</label>
                    <input type="text" name="user_name" class="form__input" id="user_name" value="<?= getFormData('user_name'); ?>">
                    <span class="err-msg"><?= getErrMsg('user_name'); ?></span>
                </div>
                <div class="form__item">
                    <label for="description" class="form__label">自己紹介</label>
                    <textarea name="description" class="form__textarea" id="description"><?= getFormData('description'); ?></textarea>
                    <div class=""><span class="">0</span>/100</div>
                    <span class="err-msg"><?= getErrMsg('description'); ?></span>
                </div>
                <div class="form__item">
                    <label for="mail_address" class="form__label">メールアドレス</label>
                    <input type="email" name="mail_address" class="form__input" id="mail_address" value="<?= getFormData('mail_address'); ?>">
                    <span class="err-msg"><?= getErrMsg('mail_address'); ?></span>
                </div>
                <div class="form__item">
                    <label for="github" class="form__label">Github</label>
                    <input type="text" name="github" class="form__input" id="github" value="<?= getFormData('github'); ?>">
                    <span class="err-msg"><?= getErrMsg('github'); ?></span>
                </div>
                <div class="form__item">
                    <label for="facebook" class="form__label">Facebook</label>
                    <input type="text" name="facebook" class="form__input" id="facebook" value="<?= getFormData('facebook'); ?>">
                    <span class="err-msg"><?= getErrMsg('facebook'); ?></span>
                </div>
                <div class="form__item">
                    <label for="twitter" class="form__label">Twitter</label>
                    <input type="text" name="twitter" class="form__input" id="twitter" value="<?= getFormData('twitter'); ?>">
                    <span class="err-msg"><?= getErrMsg('twitter'); ?></span>
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