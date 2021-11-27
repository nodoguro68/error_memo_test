<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../function/folder.php';

$user_id = $_SESSION['user_id'];
$folders = getFolders($user_id);

if (!empty($_GET['folder_id'])) {

    $folder_id = filter_input(INPUT_GET, 'folder_id');
    $folder = getFolder($folder_id, $user_id);
    $delete_folder_id = $folder['id'];
    $selected_folder_title = $folder['title'];
}

if (!empty($_POST['create_folder'])) {

    $folder = trim(filter_input(INPUT_POST, 'create_folder'));

    validMaxLen($folder, 'create_folder');
    validFolderDup($user_id, $folder);

    if (empty($err_msg)) {

        $folder_id = createFolder($user_id, $folder);

        if(!empty($folder_id)) {
            header('Location: mypage.php?folder_id='.$folder_id);
        }
    }
}

if (!empty($_POST['delete_folder'])) {

    $folder_id = filter_input(INPUT_POST, 'delete_folder');

    deleteFolder($folder_id, $user_id);

    header('Location: mypage.php');
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

        <ul class="">
            <?php if (!empty($folders)) : ?>
                <?php foreach ($folders as $folder) : ?>
                    <li class=""><a href="mypage.php?folder_id=<?= sanitize($folder['id']); ?>" class=""><?= sanitize($folder['title']); ?><span class=""></span></a></li>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>

        <form method="post" class="">
            <input type="text" name="create_folder" class="form__input" placeholder="フォルダを作成">
            <input type="submit" value="＋">
            <span class="err-msg"><?= getErrMsg('create_folder'); ?></span>
        </form>

        <section class="section">
            <div class="section__header">
                <?php if (!empty($selected_folder_title)) : ?>
                    <h2 class=""><?= sanitize($selected_folder_title); ?></h2>
                    <form method="post" class="">
                        <button type="submit" name="delete_folder" value="<?= sanitize($delete_folder_id); ?>">削除</button>
                    </form>
                <?php else : ?>
                    <h2>フォルダが選択されていません</h2>
                <?php endif; ?>
            </div>
            <div class="section__body"></div>
            <div class="section__footer"></div>
        </section>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>