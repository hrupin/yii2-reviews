<?php

/* @var $this yii\web\View */
/* @var $searchModel hrupin\reviews\models\ReviewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?php
    if($enableReviews){
        echo $this->render('_form', ['model' => $model, 'options' => $options, 'stars' => $stars]);
    }

?>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="col-md-12">
            <div class="blog-reviews">
                <hr/>
                <?= $this->render('_block', ['reviews' => $reviews]); ?>
            </div>
        </div>
    </div>
</div>