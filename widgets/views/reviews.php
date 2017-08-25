<?php

use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $reviews hrupin\reviews\models\Reviews */
/* @var boolean $enableReviews */
/* @var array $stars */

//$js = 'function refresh() {
//     $.pjax.reload({container:"#reviews"});
//     setTimeout(refresh, 5000); // restart the function every 5 seconds
// }
// refresh();';
//$this->registerJs($js, $this::POS_READY);

?>
    <?php
        if($enableReviews && Yii::$app->user->isGuest){
            Pjax::begin(['enablePushState' => false, 'id'=>'form-reviews']);
                echo $this->render('_form', ['model' => $model, 'options' => $options, 'stars' => $stars]);
            Pjax::end();
        }
    ?>
<?php Pjax::begin(['id'=>'reviews']); ?>
    <div id="flavor-nav">
        <span rel="all" class="current">All</span>
        <?php
            $e = 1; $count = count($stars);
            for($e; $e <= $count; $e++){
                echo '<span rel="r_'.$e.'" class="current">'.$stars[$e].'</span>';
            }
        ?>
    </div>
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