<?php

require_once '../common/common.php';
require_once '../common/validation.php';
require_once '../common/auth.php';
require_once '../function/user.php';
require_once '../function/category.php';
require_once '../function/folder.php';
require_once '../function/memo.php';

$user_id = (int)$_SESSION['user_id'];
$memos = getMemos();
$categories = getCategories();

if (!empty($_GET['q'])) {

    $q = ($_GET['q'] !== ' ' ? trim(filter_input(INPUT_GET, 'q')) : false );
    $category_id = filter_input(INPUT_GET, 'category_id');
    $sort = filter_input(INPUT_GET, 'sort');

    if (empty($err_msg)) {

        $search_result = searchMemo($q, $category_id, $sort);
    }
}

$page_title = 'トップページ';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <section class="section">
            <div class="section__header">
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

            </div>
            <div class="section__body">
                <?php include '../template/err_msg_area.php'; ?>
                <ul class="memo-list">

                    <?php if (count($search_result['memo']) > 0) : ?>
                        <?php foreach ($search_result['memo'] as $result) : ?>
                            <li class="memo-list__item">
                                <?php if ($user_id === $result['user_id']) : ?>
                                    <a href="memo_form.php?memo_id=<?= sanitize($result['id']); ?>" class="memo-list__link"><?= sanitize($result['title']) . '/' . sanitize($result['user_name']) . '/' . sanitize($result['created_at']); ?></a>
                                <?php else : ?>
                                    <a href="memo_detail.php?memo_id=<?= sanitize($result['id']); ?>" class="memo-list__link"><?= sanitize($result['title']) . '/' . sanitize($result['user_name']) . '/' . sanitize($result['created_at']); ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    /
                    <?php if (!empty($memos)) : ?>
                        <?php foreach ($memos as $memo) : ?>
                            <li class="memo-list__item">
                                <?php if ($user_id === $memo['user_id']) : ?>
                                    <a href="memo_form.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']) . '/' . sanitize($memo['user_name']) . '/' . sanitize($memo['created_at']); ?></a>
                                <?php else : ?>
                                    <a href="memo_detail.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']) . '/' . sanitize($memo['user_name']) . '/' . sanitize($memo['created_at']); ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>メモがありません</li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="section__footer"></div>
        </section>

    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>