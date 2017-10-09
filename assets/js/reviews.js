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
        $(".blog-reviews ul li.all").not("."+newSelection).slideUp();
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
$(function(){$('#reviews-text').keyup(function(){
    var text = $(this).val().toLowerCase(),
        spout='http,url,.ru,.com,.net,.tk,.ucoz,www,.ua,.tv,.info,.org,.su,.ру,.су,.ком,.инфо,//,.fr,точкаru'.split(',');
    for(n = 0; n < spout.length; n++){
        if(text.search(spout[n])!= -1) {
            $(this).val(text.replace(spout[n],'[Запрещено]'));
        return true;
        }
    }}
    );
});
