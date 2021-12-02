<?php

require_once '../common/common.php';
require_once '../function/memo.php';

if (!empty($_GET['memo_id'])) {

    
    $memo_id = filter_input(INPUT_GET, 'memo_id');
    $memo = getMemo($memo_id);
    $memo_count = countFavoriteMemo($memo_id);

    if(empty($memo)) {
        header('Location: 404.php');
    }

    $created_at = substr(sanitize($memo['created_at']), 0, 10);
}


$page_title = 'メモ詳細ページ';
require_once '../template/head.php';
require_once '../template/header.php';
?>

<main class="main">
    <div class="container">
        <div class="link__container">
            <a href="index.php" class="link">戻る</a>
        </div>

        <section class="section">
            <a href="user_detail.php?user_id=<?= sanitize($memo['user_id']); ?>"><img src="<?= sanitize($memo['profile_img']); ?>" alt="プロフィール画像" class=""></a>
            <p class=""><?= sanitize($memo['user_name']); ?></p>
        </section>

        <section class="section">
            <div class="section__header">
                <h2><?= sanitize($memo['title']); ?></h2>
                <span class="date"><?= $created_at; ?></span>
                <span class="category"><?= sanitize($memo['category_title']); ?></span>
            </div>
            <div class="section_body">
                <p><?= sanitize($memo['ideal']); ?></p>
                <p><?= sanitize($memo['attempt']); ?></p>
                <p><?= sanitize($memo['solution']); ?></p>
                <p><?= sanitize($memo['reference']); ?></p>
                <p><?= sanitize($memo['etc']); ?></p>
            </div>
            <div class="section_footer">
                <button type="button" class="btn-favorite js-click-favorite <?php if (isFavoriteMemo($memo['id'], $_SESSION['user_id'])) echo 'active'; ?>" data-memoid="<?= sanitize($memo['id']); ?>">いいね</button>
                <div class="count-area">
                    <span class="count"><?= $memo_count; ?></span>
                </div>
            </div>
        </section>
    </div>

</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../resource/js/ajax_favorite_memo.js"></script>
</body>

</html>