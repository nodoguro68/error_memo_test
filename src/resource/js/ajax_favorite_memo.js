$(function() {
    
    const favoriteBtn = document.querySelector('.js-click-favorite') || null;
    let = favoriteMemoId = favoriteBtn.dataset.memoid || null;
    const favoriteNum = document.querySelector('.count');

    if(favoriteMemoId !== undefined && favoriteMemoId !== null) {

        favoriteBtn.addEventListener('click', function() {

            const that = this;

            $.ajax({
                url: 'ajax_favorite_memo.php',
                type: 'POST',
                data: {
                    memoId: favoriteMemoId
                }
            }).done(function(data){
                that.classList.toggle('active');
                favoriteNum.innerHTML = data;
            }).fail(function(msg){
                console.log('Ajax error');
            });
        });
    }

});