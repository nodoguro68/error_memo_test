<?php

function pagination($current_page_num, $total_page_num, $page_col_num = 5)
{
    // もし現在のページと総ページ数が同じかつ、全てのページ数がページネーション表示数より多い場合 = 5のとき
    if ($current_page_num == $total_page_num && $total_page_num >= $page_col_num) {
        // 最小のページ数を現在のページ数-4とする
        $min_page_num = $current_page_num - 4; // 1
        // 最大のページ数を現在のページ数とする
        $max_page_num = $current_page_num; // 5

        // 現在のページ数が総ページ数ー1かつ、総ページ数がページネーション表示数より多い場合 = 4のとき
    } elseif ($current_page_num == ($total_page_num - 1) && $total_page_num >= $page_col_num) {
        // 最小のページ数を現在のページ数-3とする
        $min_page_num = $current_page_num - 3; // 1
        // 最大のページ数を現在のページ数＋1とする
        $max_page_num = $current_page_num + 1; // 5

        // 現在のページ数が2かつ、総ページ数がページネーション表示数より多い場合 = 2
    } elseif ($current_page_num == 2 && $total_page_num >= $page_col_num) {
        // 最小のページ数を現在のページ数-1とする
        $min_page_num = $current_page_num - 1; // 1
        // 最大のページ数を現在のページ数＋3とする
        $max_page_num = $current_page_num + 3; // 5

        // 現在のページ数が1かつ、総ページ数がページネーション表示数より多い場合 = 1
    } elseif ($current_page_num == 1 && $total_page_num >= $page_col_num) {
        // 最小のページ数を現在のページ数の1とする
        $min_page_num = $current_page_num; // 1
        // 最大のページ数を5とする
        $max_page_num = 5; // 5

        // 総ページ数がページネーション表示数より少ない場合 = 1,2,3,4
    } elseif ($total_page_num < $page_col_num) {
        // 最小のページ数を1とする
        $min_page_num = 1;
        // 最大のページ数を総ページ数とする
        $max_page_num = $total_page_num;

        // それ以外 
        // 3のとき、1、2、4、5を表示
        // 6のとき、4、5、7、8を表示
    } else {
        // 最小のページ数を現在のページ数-2とする
        $min_page_num = $current_page_num - 2;
        // 最大のページ数を現在のページ数＋2とする
        $max_page_num = $current_page_num + 2;
    }

    echo '<div class="pagination">';
    echo '<ul class="pagination-list">';
    
    // もし現在のページ数が1でない場合
    if ($current_page_num != 1) {
        $prev_page_num = $current_page_num - 1;
        // 1ページ戻るボタンを表示する
        echo '<li class="list-item"><a href="?p=1">&lt;&lt;</a></li>';
        echo '<li class="list-item"><a href="?p=' . $prev_page_num . '">&lt;</a></li>';
    }

    // 最小ページ数が最大ページ数と同じになるまでループ
    for ($i = $min_page_num; $i <= $max_page_num; $i++) {
        echo '<li class="list-item ';

        // 最小ページ数が現在のページ数と同じなら
        if ($current_page_num == $i) {
            // class名にactiveを追加する
            echo 'active';
        }
        echo '"><a href="?p=' . $i . '">' . $i . '</a></li>';
    }

    // 現在のページ数が最大ページ数ではないかつ、最大ページ数が1より多い場合
    if ($current_page_num != $max_page_num && $max_page_num > 1) {
        $next_page_num = $current_page_num + 1;
        echo '<li class="list-item"><a href="?p=' . $next_page_num .'">&gt;</a></li>';
        echo '<li class="list-item"><a href="?p=' . $total_page_num .'">&gt;&gt;</a></li>';
    }
    echo '</ul>';
    echo '</div>';
}