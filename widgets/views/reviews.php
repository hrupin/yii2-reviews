<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var boolean $enableReviews */
/* @var array $stars */

?>
<?php
    if($enableReviews && !Yii::$app->user->isGuest && $userCountReviews < 10){
        Pjax::begin(['enablePushState' => false, 'id'=>'form-reviews']);
            echo $this->render('_form', [
                'model' => $model,
                'options' => $options,
                'stars' => $stars,
                'emailAuthor' => $emailAuthor
            ]);
        Pjax::end();
    }
?>
<?php Pjax::begin(['id'=>'reviews']); ?>
    <div class="bootstrap snippet">
        <div class="row">
            <div class="col-md-12">
                <div id="flavor-nav">
                    <span rel="all" class="current spanStatistics"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></span>
                    <?php
                    $e = 1; $count = count($stars);
                    for($e; $e <= $count; $e++){
                        echo '<span rel="r_'.$e.'" class="current spanStatistics">'.$stars[$e].' '.\hrupin\reviews\models\Reviews::find()->countActiveReviewsForPageAndMainLevelForRating($model->page, $model->type, $e).'</span>';
                    }
                    ?>
                </div>
                <div class="blog-reviews">
                    <hr/>
                    <?= $this->render('_block', [
                        'reviews' => $reviews,
                        'model' => $model,
                        'options' => $options,
                        'stars' => $stars,
                        'enableReviews' => $enableReviews
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
<?php
if(isset($allowResponse) && !$allowResponse){
    $this->registerCSS('.reply{display: none !important;}');
}
?>
<?php Pjax::end(); ?>
