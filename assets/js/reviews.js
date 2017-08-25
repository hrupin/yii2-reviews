$('#rating > span').hover(
    function() {
        $s = $(this).attr('data-rating');
        $('#textRating').text($(this).attr('data-text'));
        $("#rating span").each(function(){
            if($(this).attr('data-rating') <= $s){
                $(this).addClass("glyphicon-star");
                $(this).removeClass('glyphicon-star-empty');
            }
            else{
                $(this).removeClass("glyphicon-star");
                $(this).addClass('glyphicon-star-empty');
            }
        });

    }, function() {
        $('#reviews-raiting').val($(this).attr('data-rating'));
    }
);
$('#rating > span').on('click', function () {
    $s = $(this).attr('data-rating');
    $('#textRating').text($(this).attr('data-text'));
    $("#rating span").each(function () {
        if ($(this).attr('data-rating') <= $s) {
            $(this).addClass("glyphicon-star");
            $(this).removeClass('glyphicon-star-empty');
        }
        else {
            $(this).removeClass("glyphicon-star");
            $(this).addClass('glyphicon-star-empty');
        }
    });
    $('#rating > span').off('hover');
});

$('.reply').on('click', function () {
    var id = $(this).attr('data-id');
    var input = document.createElement('input');
    input.id = 'input_'+id;
    $('.reviews_'+id).html(input);
});
function addNewReview(id) {
    $.ajax({
        type: 'POST',
        url: urlReviews,
        // github
        //data: 'idReviews='+id+'&'+csrfReviews,
        // miltor
        data: 'idReviews='+id+'&'+csrfReviews+'&pageIdentifier='+pageIdentifier+'&reviewsIdentifier='+reviewsIdentifier,
        success: function(data){
            console.log(data);
        }
    });
}
