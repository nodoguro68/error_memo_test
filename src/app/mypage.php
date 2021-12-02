<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../common/validation.php';
require_once '../function/user.php';
require_once '../function/category.php';
require_once '../function/folder.php';
require_once '../function/memo.php';

$user_id = $_SESSION['user_id'];
$categories = getCategories();
$folders = getFolders($user_id);
$unsolved_memos = getUnsolvedMemos($user_id);
$solved_memos = getSolvedMemos($user_id);
$favorite_memos = getFavoriteMemos($user_id);
$db_column = 'user_name, profile_img';
$user_info = getUserInfo($user_id, $db_column);

if (!empty($_GET['folder_id'])) {

    $folder_id = filter_input(INPUT_GET, 'folder_id');
    $folder = getFolder($folder_id, $user_id);
    $delete_folder_id = $folder['id'];
    $selected_folder_title = $folder['title'];

    $memos = getMemoInFolder($user_id, $folder_id);
}

if (!empty($_POST['create_folder'])) {

    $folder = trim(filter_input(INPUT_POST, 'create_folder'));

    validMaxLen($folder, 'create_folder');
    validFolderDup($user_id, $folder);

    if (empty($err_msg)) {

        $folder_id = createFolder($user_id, $folder);

        if (!empty($folder_id)) {
            header('Location: mypage.php?folder_id=' . $folder_id);
        }
    }
}

if (!empty($_POST['delete_folder'])) {

    $folder_id = filter_input(INPUT_POST, 'delete_folder');

    deleteFolder($folder_id, $user_id);
    deleteMemosInFolder($folder_id);

    header('Location: mypage.php');
}

if (!empty($_GET['q'])) {

    $q = ($_GET['q'] !== ' ' ? trim(filter_input(INPUT_GET, 'q')) : false);
    $category_id = filter_input(INPUT_GET, 'category_id');
    $sort = filter_input(INPUT_GET, 'sort');

    if (empty($err_msg)) {

        $search_result = searchMyMemo($user_id, $q, $category_id, $sort);

    }
}

$page_title = 'マイページ';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <ul class="tab-menu">
            <li class="">フォルダ</li>
            <li class="">未解決</li>
            <li class="">解決済み</li>
            <li class="">お気に入り</li>
            <li class="">検索</li>
            <li class="">設定</li>
        </ul>

        <!-- 設定 -->
        <div class="user-info">
            <img src="<?= sanitize($user_info['profile_img']); ?>" alt="プロフィール画像" class="">
            <p class=""><?= sanitize($user_info['user_name']); ?></p>
        </div>

        <ul class="">
            <li class=""><a href="profile.php" class="">プロフィール編集</a></li>
            <li class=""><a href="edit_pass.php" class="">パスワード変更</a></li>
            <li class=""><a href="signout.php" class="">退会</a></li>
        </ul>


        <!-- フォルダ -->
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
            <div class="section__body">
                <ul class="memo-list">
                    <?php if (!empty($memos)) : ?>
                        <?php foreach ($memos as $memo) : ?>
                            <li class="memo-list__item"><a href="memo_form.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']); ?></a></li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>メモがありません</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="section__footer"></div>
        </section>

        <?php if (!empty($folder_id)) : ?>
            <a href="memo_form.php?folder_id=<?= sanitize($folder_id); ?>" class="">メモを追加する</a>
        <?php endif; ?>

        <!-- 未解決 -->
        <ul class="memo-list">
            <?php if (!empty($unsolved_memos)) : ?>
                <?php foreach ($unsolved_memos as $memo) : ?>
                    <li class="memo-list__item"><a href="memo_form.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']); ?></a></li>
                <?php endforeach; ?>
            <?php else : ?>
                <li>メモがありません</li>
            <?php endif; ?>
        </ul>

        <!-- 解決済み -->
        <ul class="memo-list">
            <?php if (!empty($solved_memos)) : ?>
                <?php foreach ($solved_memos as $memo) : ?>
                    <li class="memo-list__item"><a href="memo_form.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']); ?></a></li>
                <?php endforeach; ?>
            <?php else : ?>
                <li>メモがありません</li>
            <?php endif; ?>
        </ul>

        <!-- お気に入り -->
        <ul class="memo-list">
            <?php if (!empty($favorite_memos)) : ?>
                <?php foreach ($favorite_memos as $memo) : ?>
                    <li class="memo-list__item"><a href="memo_detail.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']); ?>/<?= sanitize($memo['created_at']); ?></a></li>
                <?php endforeach; ?>
            <?php else : ?>
                <li>メモがありません</li>
            <?php endif; ?>
        </ul>

        <!-- 検索 -->
        <form method="get" name="search" class="">
            <input type="search" name="q" value="<?= getFormData('q', true); ?>" placeholder="エラーを検索">
            <select name="category_id" id="" class="">
                <option value="0" <?php if (getFormData('category_id', true) == 0) echo 'selected'; ?>>未選択</option>
                <?php if (!empty($categories)) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= sanitize($category['id']); ?>" <?php if (getFormData('category_id', true) == sanitize($category['id'])) echo 'selected'; ?>><?= sanitize($category['title']); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <select name="sort" id="" class="">
                <option value="0" <?php if (getFormData('sort', true) == 0) echo 'selected'; ?>>未選択</option>
                <option value="new" <?php if (getFormData('sort', true) == 'new') echo 'selected'; ?>>新しい順</option>
                <option value="old" <?php if (getFormData('sort', true) == 'old') echo 'selected'; ?>>古い順</option>
            </select>
            <input type="submit" value="検索">
        </form>

        <?php if (count($search_result['memo']) > 0) : ?>
            <?php foreach ($search_result['memo'] as $result) : ?>
                <li class="memo-list__item">
                    <a href="memo_form.php?memo_id=<?= sanitize($result['id']); ?>" class="memo-list__link"><?= sanitize($result['title']) . '/' . sanitize($result['created_at']); ?></a>
                </li>
            <?php endforeach; ?>
        <?php elseif(count($search_result['memo']) == 0 && !empty($_GET['search'])): ?>
            <li>検索結果がありません</li>
        <?php endif; ?>

    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>