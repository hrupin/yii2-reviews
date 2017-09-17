<?php

use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use hrupin\reviews\models\Reviews;
use hrupin\reviews\ReviewsAsset;

/* @var $this yii\web\View */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $stars array */

ReviewsAsset::register($this);

$this->title = Yii::t('reviews', 'Reviews');
$this->params['breadcrumbs'] = [
    [
        'label' => Yii::t('reviews', 'Reviews'),
        'url' => ['/reviews/admin/index'],
    ],
    [
        'label' => Yii::$app->request->get('type')
    ],
    [
        'label' => Yii::$app->request->get('page')
    ]
];

$template = '<img src="{img}" class="avatar img-rounded" alt="">
             <div class="review" id="{identifier}">
                 <p class="meta">{date} <a href="#"> {stars} {name}</a> {says} : <i class="pull-right">{delete} {edit} {success}<span class="reply" data-id="{idReviews}"><small>{reply}</small></span></i></p>
                 <p>{text}</p>
             </div>';

Reviews::generateHTML($template, $reviews, 'ul', 'li', 1);

echo '<a href="'. Url::toRoute(Yii::$app->request->get('page')) .'">'. Yii::t('reviews', 'View page') .'</a>';


Pjax::begin(['id'=>'reviews']);

echo '<div class="blog-reviews">';
echo Reviews::$html;
echo '</div>';

Pjax::end();

$this->registerJs(
    '$("document").ready(function(){
        $(".edit").on("click", function(){
            document.getElementById("hiddenId").value = $(this).attr("data-id");
            document.getElementById("hiddenDo").value = "edit";
            document.getElementById("hiddenFormReviews").submit();
        });
        $(".success").on("click", function(){
            document.getElementById("hiddenId").value = $(this).attr("data-id");
            document.getElementById("hiddenDo").value = "success";
            document.getElementById("hiddenFormReviews").submit();
        });
        $(".delete").on("click", function () {
            $( ".responseForms" ).remove();
            var id = $(this).attr("data-id");
            $.ajax({
                type: "POST",
                url: "'.Url::toRoute(["/reviews/admin/delete"]).'",
                data: "reviews_id="+id,
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
     });'
);
?>

<?php $form = ActiveForm::begin(['method' => 'GET', 'options' => ['id' => 'hiddenFormReviews'], 'action' => ['/reviews/admin/update']]); ?>
<input id="hiddenId" name="id" type="hidden">
<input id="hiddenDo" name="do" type="hidden">
<?php ActiveForm::end(); ?>