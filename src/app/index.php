<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../function/user.php';
require_once '../function/folder.php';
require_once '../function/memo.php';

$user_id = (int)$_SESSION['user_id'];
$memos = getMemos();


$page_title = 'トップページ';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <section class="section">
            <div class="section__header">

            </div>
            <div class="section__body">
                <ul class="memo-list">
                    <?php if (!empty($memos)) : ?>
                        <?php foreach ($memos as $memo) : ?>
                            <li class="memo-list__item">
                                <?php if ($user_id === $memo['user_id']) : ?>
                                    <a href="memo_form.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']).'/'.sanitize($memo['user_name']).'/'.sanitize($memo['created_at']); ?></a>
                                <?php else : ?>
                                    <a href="memo_detail.php?memo_id=<?= sanitize($memo['id']); ?>" class="memo-list__link"><?= sanitize($memo['title']).'/'.sanitize($memo['user_name']).'/'.sanitize($memo['created_at']); ?></a>
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