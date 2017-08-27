<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var array $stars */

?>

<?php Pjax::begin(['id'=>'reviews']); ?>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-md-12">
            <div id="flavor-nav">
                <span rel="all" class="current"><span class="glyphicon glyphicon-th-large" aria-hidden="true"></span></span>
                <?php
                $e = 1; $count = count($stars);
                for($e; $e <= $count; $e++){
                    echo '<span rel="r_'.$e.'" class="current">'.$stars[$e].' '.\hrupin\reviews\models\Reviews::find()->countActiveReviewsForPageAndMainLevelForRating($model->page, $model->type, $e).'</span>';
                }
                ?>
            </div>
            <div class="blog-reviews">
                <hr/>
                <?= $this->render('_block', [
                    'reviews' => $reviews
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
