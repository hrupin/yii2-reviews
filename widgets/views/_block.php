<?php

use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $stars array */

$template = '<img src="{img}" class="avatar img-rounded" alt="">
             <div class="review" id="{identifier}">
                 <p class="meta">{date} <a href="#"> {stars} {name}</a> {says} : <i class="pull-right">{delete} {edit} <span class="reply" data-id="{idReviews}"><small>{reply}</small></span></i></p>
                 <p>
                 {text}
                 {data}
                 </p>
             </div>';
$class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
$modelClass = Yii::createObject($class::className());
$modelClass::generateHTML($template, $reviews, 'ul', 'li', 1, $allowResponse);
echo $modelClass::$html;
$this->registerJs(
    '$("document").ready(function(){
        $("#form-reviews").on("pjax:end", function() {
            $.pjax.reload({container:"#reviews"});
        });
        $(".reply").on("click", function () {
            $( ".responseForms" ).remove();
            var id = $(this).attr("data-id");
            var form = document.createElement("form");
            form.innerHTML = \'<div>\' +
                    \'<textarea class="responseText" placeholder="..."></textarea>\' +
                    \'<span class="buttonSend btn btn-default" data-id="\' + id + \'">\' +
                    \'<span class="glyphicon glyphicon-send" aria-hidden="true"></span></span>\' +
                    \'</div>\';
            form.action= urlReviewsCreate;
            form.className = "responseForms";
            insertAfter(form, document.getElementById("reviews_"+id));
            $(".buttonSend").on("click", function () {
                var text = $(".responseText").val(),
                    id = $(this).attr("data-id"),
                    csrf = "&"+$("meta[name=csrf-param]").prop("content")+"="+$("meta[name=csrf-token]").prop("content");
                $.ajax({
                    type: "POST",
                    url: urlReviewsCreate,
                    data: "reviews_id="+id+"&text="+text+csrf,
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
        $(".edit").on("click", function () {
            $( ".responseForms" ).remove();
            var id = $(this).attr("data-id"),
                csrf = "&"+$("meta[name=csrf-param]").prop("content")+"="+$("meta[name=csrf-token]").prop("content");
            $.ajax({
                type: "POST",
                url: urlReviewsUpdateResponse,
                data: "reviews_id="+id+csrf,
                success: function(data){
                    if(data.text === undefined && data.edit === undefined){
                        var wrap = document.createElement("div");
                        wrap.innerHTML = data;
                        insertAfter(wrap, document.getElementById("reviews_" + id));
                    }
                    else if(data.edit === \'review\') {
                        document.getElementById(\'hiddenData\').value = data.response;
                        document.getElementById(\'hiddenURL\').value = location.href;
                        document.getElementById(\'hiddenFormReviews\').submit();
                    }
                    else {
                        var form = document.createElement("form");
                        form.innerHTML = \'<div>\' +
                            \'<textarea class="responseText" placeholder="...">\' + data.response.text + \'</textarea>\' +
                            \'<span class="buttonSendUpdate btn btn-default" data-id="\' + data.response.reviews_id + \'">\' +
                            \'<span class="glyphicon glyphicon-send" aria-hidden="true"></span></span>\' +
                            \'</div>\';
                        form.action = urlReviewsCreate;
                        form.className = "responseForms";
                        insertAfter(form, document.getElementById("reviews_" + data.response.reviews_id));
                        $(".buttonSendUpdate").on("click", function () {
                            var text = $(".responseText").val(),
                                id = $(this).attr("data-id"),
                                csrf = "&"+$("meta[name=csrf-param]").prop("content")+"="+$("meta[name=csrf-token]").prop("content");
                            $.ajax({
                                type: "POST",
                                url: urlReviewsCreate,
                                data: "reviews_id=" + id + "&text=" + text+csrf,
                                success: function (data) {
                                    if (data.status == "success" && data.reload) {
                                        $.pjax.reload({container: "#reviews"});
                                    }
                                    else {
                                        $(".responseForms").html(data.message);
                                    }
                                }
                            });
                        });
                    }
                }
            });
        });
        $(".delete").on("click", function () {
            $( ".responseForms" ).remove();
            var id = $(this).attr("data-id"),
            csrf = "&"+$("meta[name=csrf-param]").prop("content")+"="+$("meta[name=csrf-token]").prop("content");
            $.ajax({
                type: "POST",
                url: urlReviewsDelete,
                data: "reviews_id="+id+csrf,
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
        var urlReviewsCreate = "'.Url::toRoute(["/reviews/reviews/create-response"]).'";
        var urlReviewsUpdateResponse = "'.Url::toRoute(["/reviews/reviews/update-review"]).'";
        var urlReviewsDelete = "'.Url::toRoute(["/reviews/reviews/delete-review"]).'";
    });'
);
?>

<?php $form = ActiveForm::begin(['options' => ['id' => 'hiddenFormReviews'], 'action' => ['reviews/reviews/update-review']]); ?>
<input id="hiddenData" name="hiddenData" type="hidden">
<input id="hiddenURL" name="hiddenURL" type="hidden">
<?php ActiveForm::end(); ?>
