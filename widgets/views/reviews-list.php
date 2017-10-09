<?php

use yii\widgets\Pjax;
use hrupin\reviews\models\Reviews;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var array $stars */

$class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
$modelReviews = Yii::createObject($class::className());

?>
<h2><?= Yii::t('reviews', 'Reviews'); ?></h2>
<?php Pjax::begin(['id'=>'reviews']); ?>
<div class="bootstrap snippet">
    <div class="row">
        <div class="col-md-12">
            <div id="flavor-nav">
                <span rel="all" class="current spanStatistics"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></span>
                <?php
                $e = 1; $count = count($stars);
                for($e; $e <= $count; $e++){
                    $c = 0;
                    foreach ($model->page as $item) {
                        $c += $modelReviews->find()->countActiveReviewsForPageAndMainLevelForRating($item, $model->type, $e);
                    }
                    echo '<span rel="r_'.$e.'" class="current spanStatistics">'.$stars[$e].' '.$c.'</span>';
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
                    'enableReviews' => true
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
