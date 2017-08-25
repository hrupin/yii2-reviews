<?php

use yii\helpers\Url;
use hrupin\reviews\models\Reviews;

/* @var $this yii\web\View */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var string $pageIdentifier */ // удалить на гитхабе
/* @var string $reviewsIdentifier */ // удалить на гитхабе

$template = '<img src="{img}" class="avatar img-rounded" alt="">
             <div class="review">
                 <p class="meta">{date} <a href="#">{name}</a> {says} : <i class="pull-right"><span class="reply" data-id="{idReviews}"><small>{reply}</small></span></i></p>
                 <p>{text}</p>
             </div>';

Reviews::generateHTML($template, $reviews, 'ul', 'li', 1, $html);

echo Reviews::$html;
?>
<script>
    var urlReviews         = '<?= Url::toRoute(['reviews/add-response-review']); ?>',
        csrfReviews        = '<?= Yii::$app->getRequest()->csrfParam; ?>=<?= Yii::$app->getRequest()->getCsrfToken(); ?>',
</script>