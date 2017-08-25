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
        $('#reviews-rating').val($(this).attr('data-rating'));
    }, function() {

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
$(function() {
    var newSelection = "";
    $("#flavor-nav span").click(function(){
        $(".blog-reviews ul").fadeTo(200, 0.10);
        $("#flavor-nav span").removeClass("current");
        $(this).addClass("current");
        newSelection = $(this).attr("rel");
        $(".blog-reviews ul li").not("."+newSelection).slideUp();
        $("."+newSelection).slideDown();
        $(".blog-reviews ul").fadeTo(600, 1);
    });
});
function insertAfter( node, referenceNode ) {
    if ( !node || !referenceNode ) return;
    var parent = referenceNode.parentNode, nextSibling = referenceNode.nextSibling;
    if ( nextSibling && parent ) {
        parent.insertBefore(node, referenceNode.nextSibling);
    } else if ( parent ) {
        parent.appendChild( node );
    }
}


$('.reply').on('click', function () {
    $( ".responseForms" ).remove();
    var id = $(this).attr('data-id');
    var form = document.createElement('form');
    form.innerHTML = "<form data-pjax>" +
        "<textarea placeholder='...'></textarea>" +
        "<button class='buttonSend'>send</button>" +
        "</form>";
    form.action= urlReviews;
    form.className = 'responseForms';
    insertAfter(form, document.getElementById('reviews_'+id));
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
