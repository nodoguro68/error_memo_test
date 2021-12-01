<?php

require_once '../common/common.php';
require_once '../function/user.php';
require_once '../function/memo.php';

if (!empty($_GET['user_id'])) {
    $user_id = filter_input(INPUT_GET, 'user_id');
    $db_column = 'user_name, profile_img, github, facebook, twitter';
    $user_info = getUserInfo($user_id, $db_column);
    $memos = getUsersMemos($user_id);
    $favorite_memos = getFavoriteMemos($user_id);
}


$page_title = 'ユーザー詳細ページ';
require_once '../template/head.php';
require_once '../template/header.php';
?>

<main class="main">
    <div class="container">
        <div class="link__container">
            <a href="index.php" class="link">戻る</a>
        </div>


        <!-- ユーザー情報 -->
        <div class="user-info">
            <img src="<?= sanitize($user_info['profile_img']); ?>" alt="プロフィール画像" class="">
            <p class=""><?= sanitize($user_info['user_name']); ?></p>
            <p class=""><a href="<?= (!empty($user_info['github']) ? sanitize($user_info['github']) : '#'); ?>">Github</a></p>
            <p class=""><a href="<?= (!empty($user_info['facebook']) ? sanitize($user_info['facebook']) : '#'); ?>">Facebook</a></p>
            <p class=""><a href="<?= (!empty($user_info['twitter']) ? sanitize($user_info['twitter']) : '#'); ?>">Twitter</a></p>
        </div>


        <section class="section">
            <div class="section__header">
                <ul class="">
                    <li class="">メモ</li>
                    <li class="">お気に入り</li>
                </ul>
                <!-- メモ件数 -->
            </div>
            <div class="section_body">
                <!-- ユーザーのメモ -->
                <ul class="memo-list">
                    <?php if (!empty($memos)) : ?>
                        <?php foreach ($memos as $memo) : ?>
                            <li class="memo-list__item"><a href="memo_detail.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']); ?></a></li>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <li>メモがありません</li>
                    <?php endif; ?>
                </ul>

                <!-- ユーザーのお気に入りメモ -->
                <ul class="">
                    <?php if (!empty($favorite_memos)) : ?>
                        <?php foreach ($favorite_memos as $memo) : ?>
                            <li class="memo-list__item">
                                <?php if ($_SESSION['user_id'] === $memo['user_id']) : ?>
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
            <div class="section_footer">
            </div>
        </section>
    </div>

</main>

</body>

</html>