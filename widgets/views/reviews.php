<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var boolean $enableReviews */
/* @var array $stars */

?>
<?php Pjax::begin(); ?>
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
                    <?= $this->render('_block', [
                        'reviews' => $reviews
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end(); ?>