<?php

require_once '../common/common.php';
require_once '../common/auth.php';
require_once '../common/validation.php';
require_once '../function/memo.php';
require_once '../function/category.php';

$user_id = $_SESSION['user_id'];
$folder_id = filter_input(INPUT_GET, 'folder_id');
$memo_id = (!empty($_GET['memo_id'])) ? filter_input(INPUT_GET, 'memo_id') : '';
$db_form_data = (!empty($memo_id)) ? getMyMemo($memo_id, $user_id) : '';
$editFlag = (!empty($db_form_data)) ? true : false;
$categories = getCategories();

if (!empty($_POST['edit_memo'])) {

    $memo_data = array(
        'id' => $memo_id,
        'user_id' => $user_id,
        'folder_id' => $folder_id,
        'category_id' => $_POST['category_id'],
        'title' => $_POST['title'],
        'ideal' => $_POST['ideal'],
        'attempt' => $_POST['attempt'],
        'solution' => $_POST['solution'],
        'reference' => $_POST['reference'],
        'etc' => $_POST['etc'],
        'is_solved' => $_POST['is_solved'],
        'is_published' => $_POST['is_published'],
    );

    if ($db_form_data['title'] !== $memo_data['title']) {
        validRequired($memo_data['title'], 'title');
    }
    if ($db_form_data['is_published'] !== $memo_data['is_published']) {
        validPublished($memo_data['is_published'], $memo_data['is_solved']);
    }
    if ($db_form_data['is_solved'] !== $memo_data['is_solved']) {
        validSolved($memo_data['solution'], $memo_data['is_solved']);
    }
    if ($db_form_data['solution'] !== $memo_data['solution']) {
        validSolved($memo_data['solution'], $memo_data['is_solved']);
    }

    if (empty($err_msg)) {

        if (editMemo($memo_data)) {
            header('Location: mypage.php?folder_id=' . $folder_id);
        }
    }
}
if (!empty($_POST['create_memo'])) {

    $memo_data = array(
        'id' => $memo_id,
        'user_id' => $user_id,
        'folder_id' => $folder_id,
        'category_id' => $_POST['category_id'],
        'title' => $_POST['title'],
        'ideal' => $_POST['ideal'],
        'attempt' => $_POST['attempt'],
        'solution' => $_POST['solution'],
        'reference' => $_POST['reference'],
        'etc' => $_POST['etc'],
        'is_solved' => $_POST['is_solved'],
        'is_published' => $_POST['is_published'],
    );

    validRequired($memo_data['title'], 'title');
    validPublished($memo_data['is_published'], $memo_data['is_solved']);
    validSolved($memo_data['solution'], $memo_data['is_solved']);

    if (empty($err_msg)) {

        if (createMemo($memo_data)) {
            header('Location: mypage.php?folder_id=' . $folder_id);
        }
    }
}

if (!empty($_POST['delete_memo'])) {

    if (deleteMemo($memo_id)) {
        header('Location: mypage.php?folder_id=' . $folder_id);
    }
}

$page_title = ($editFlag) ? 'メモ編集' : 'メモ新規登録';
include '../template/head.php';
include '../template/header.php';
?>

<main class="main">
    <div class="container">

        <a href="mypage.php?folder_id=<?= sanitize($folder_id); ?>" class="">＜ フォルダ</a>

        <form method="post" class="form">
            <div class="form__header">
                <h2 class="form__title"><?= ($editFlag) ? 'メモ編集' : 'メモ新規登録'; ?></h2>
                <button type="submit" name="delete_memo" value="<?= $memo_id; ?>">削除</button>
                <?php include '../template/err_msg_area.php' ?>
            </div>
            <div class="form__body">
                <!-- エラータイトル -->
                <div class="form__item">
                    <label for="title" class="form__label">エラータイトル</label>
                    <input type="text" name="title" class="form__input" id="title" value="<?= getFormData('title'); ?>">
                    <span class="err-msg"><?= getErrMsg('title'); ?></span>
                </div>
                <!-- 公開するかどうか -->
                <div class="form__item">
                    <label for="private" class="form__label">非公開</label><input type="radio" name="is_published" value="0" id="private" <?php if (getFormData('is_published') === '0' || getFormData('is_published') === null) echo 'checked'; ?>>
                    <label for="public" class="form__label">公開</label><input type="radio" name="is_published" value="1" id="public" <?php if (getFormData('is_published') === '1') echo 'checked'; ?>>
                    <span class="err-msg"><?= getErrMsg('is_published'); ?></span>
                </div>
                <!-- 解決済みかどうか -->
                <div class="form__item">
                    <label for="unsolved" class="form__label">未解決</label><input type="radio" name="is_solved" value="0" id="unsolved" <?php if (getFormData('is_solved') === '0' | getFormData('is_solved') === null) echo 'checked'; ?>>
                    <label for="solved" class="form__label">解決済み</label><input type="radio" name="is_solved" value="1" id="solved" <?php if (getFormData('is_solved') === '1') echo 'checked'; ?>>
                    <span class="err-msg"><?= getErrMsg('is_solved'); ?></span>
                </div>
                <!-- カテゴリー -->
                <div class="form__item">
                    <label for="category" class="form__label">カテゴリー</label>
                    <select name="category_id" id="category" class="form__select">
                        <?php if (!empty($categories)) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?= sanitize($category['id']); ?>" <?php if ((int)getFormData('category_id') === $category['id']) echo 'selected'; ?>><?= sanitize($category['title']); ?></option>
                                <?= var_dump($category['id']); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                    <span class="err-msg"><?= getErrMsg('category'); ?></span>
                </div>
                <!-- やりたいこと -->
                <div class="form__item">
                    <label for="ideal" class="form__label">やりたいこと</label>
                    <textarea name="ideal" id="ideal" class="form__textarea"><?= getFormData('ideal'); ?></textarea>
                    <span class="err-msg"><?= getErrMsg('ideal'); ?></span>
                </div>
                <!-- 試したこと -->
                <div class="form__item">
                    <label for="attempt" class="form__label">試したこと</label>
                    <textarea name="attempt" id="attempt" class="form__textarea"><?= getFormData('attempt'); ?></textarea>
                    <span class="err-msg"><?= getErrMsg('attempt'); ?></span>
                </div>
                <!-- 解決方法 -->
                <div class="form__item">
                    <label for="solution" class="form__label">解決方法</label>
                    <textarea name="solution" id="solution" class="form__textarea"><?= getFormData('solution'); ?></textarea>
                    <span class="err-msg"><?= getErrMsg('solution'); ?></span>
                </div>
                <!-- 参考 -->
                <div class="form__item">
                    <label for="reference" class="form__label">参考</label>
                    <input type="text" name="reference" class="form__input" id="reference" value="<?= getFormData('reference'); ?>">
                    <span class="err-msg"><?= getErrMsg('reference'); ?></span>
                </div>
                <!-- その他 -->
                <div class="form__item">
                    <label for="etc" class="form__label">その他</label>
                    <textarea name="etc" id="etc" class="form__textarea"><?= getFormData('etc'); ?></textarea>
                    <span class="err-msg"><?= getErrMsg('etc'); ?></span>
                </div>
                <div class="form__footer">
                    <div class="btn-container">
                        <input type="submit" name="<?= ($editFlag) ? 'edit_memo' : 'create_memo'; ?>" value="<?= ($editFlag) ? '編集' : '保存'; ?>" class="btn">
                    </div>
                </div>
            </div>
        </form>
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>