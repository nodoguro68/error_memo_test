<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../function/folder.php';

$user_id = $_SESSION['user_id'];

if (!empty($_POST['create_folder'])) {

    $folder = trim(filter_input(INPUT_POST, 'create_folder'));

    validMaxLen($folder, 'create_folder');
    validFolderDup($user_id, $folder);

    if (empty($err_msg)) {

        createFolder($user_id, $folder);

        header('Location: mypage.php');
    }
}


$page_title = 'マイページ';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">
        <ul class="">
            <li class=""><a href="profile.php" class="">プロフィール編集</a></li>
            <li class=""><a href="edit_pass.php" class="">パスワード変更</a></li>
            <li class=""><a href="signout.php" class="">退会</a></li>
        </ul>

        <form method="post" class="">
            <input type="text" name="create_folder" class="form__input" placeholder="フォルダを作成">
            <input type="submit" value="＋">
            <span class="err-msg"><?= getErrMsg('create_folder'); ?></span>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>