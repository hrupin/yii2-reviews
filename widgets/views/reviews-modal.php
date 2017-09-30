<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var boolean $enableReviews */
/* @var array $stars */

if($enableReviews && !Yii::$app->user->isGuest && $userCountReviews < 10){
    echo '<button type="button" class="btn btn-default buttonModalReviews" data-toggle="modal" data-target="#reviewsModal">' .Yii::t('reviews', 'Leave a review'). '</button>';
}

?>
<div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"><?= Yii::t('reviews', 'Add a review'); ?></h4>
      </div>
      <div class="modal-body">
        <?php Pjax::begin(['enablePushState' => false, 'id'=>'form-reviews']); ?>
        <?= $this->render('_form', [
            'model' => $model,
            'options' => $options,
            'stars' => $stars,
            'emailAuthor' => $emailAuthor
        ]); ?>
        <?php Pjax::end(); ?>
      </div>
    </div>
  </div>
</div>
