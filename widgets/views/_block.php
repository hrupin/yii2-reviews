<?php

use yii\helpers\Url;
use hrupin\reviews\models\Reviews;

/* @var $this yii\web\View */
/* @var $reviews hrupin\reviews\models\Reviews */

$template = '<img src="{img}" class="avatar img-rounded" alt="">
             <div class="review" id="{identifier}">
                 <p class="meta">{date} <a href="#"> {stars} {name}</a> {says} : <i class="pull-right"><span class="reply" data-id="{idReviews}"><small>{reply}</small></span></i></p>
                 <p>{text}</p>
             </div>';
Reviews::generateHTML($template, $reviews, 'ul', 'li', 1);
echo Reviews::$html;
$this->registerJs(
    '$("document").ready(function(){
        $("#form-reviews").on("pjax:end", function() {
            $.pjax.reload({container:"#reviews"});        
        });
        $(".reply").on("click", function () {
            $( ".responseForms" ).remove();
            console.log($(this).attr("data-id"));
            var id = $(this).attr("data-id");
            var form = document.createElement("form");
            form.innerHTML = \'<div>\' +
                \'<textarea class="responseText" placeholder="..."></textarea>\' +
                \'<span class="buttonSend btn btn-default" data-id="\'+id+\'">\' +
                \'<span class="glyphicon glyphicon-send" aria-hidden="true"></span></span>\' +
                \'</div>\';
            form.action= urlReviews;
            form.className = "responseForms";
            insertAfter(form, document.getElementById("reviews_"+id));
            $(".buttonSend").on("click", function () {
                var text = $(".responseText").val(),
                    id = $(this).attr("data-id");
                $.ajax({
                    type: "POST",
                    url: urlReviews,
                    data: "reviews_id="+id+"&text="+text,
                    success: function(data){
                        var tmp = JSON.parse(data);
                        if(tmp.status == "success" && tmp.reload){
                            $.pjax.reload({container:"#reviews"});
                        }
                        else{
                            $(".responseForms").html(tmp.message);
                        }
                    }
                });
            });
        });
        var urlReviews = "'. Url::toRoute(['reviews/reviews/create-response']).'";
    });'
);