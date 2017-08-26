<?php

use yii\helpers\Url;
use hrupin\reviews\models\Reviews;

/* @var $this yii\web\View */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var string $pageIdentifier */ // удалить на гитхабе
/* @var string $reviewsIdentifier */ // удалить на гитхабе
// reviews_'.$value['idReviews'].'
$template = '<img src="{img}" class="avatar img-rounded" alt="">
             <div class="review" id="{identifier}">
                 <p class="meta">{date} <a href="#">{name}</a> {says} : <i class="pull-right"><span class="reply" data-id="{idReviews}"><small>{reply}</small></span></i></p>
                 <p>{text}</p>
             </div>';

Reviews::generateHTML($template, $reviews, 'ul', 'li', 1);

echo Reviews::$html;
?>
<script>
    var urlReviews = '<?= Url::toRoute(['reviews/reviews/create-response']); ?>';
</script>