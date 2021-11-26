<?php

require_once '../common/common.php';
require_once '../function/user.php';
require_once '../common/auth.php';


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
    </div>

</main>
<?php include '../template/footer.php' ?>

</body>

</html>