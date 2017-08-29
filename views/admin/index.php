<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use hrupin\reviews\ReviewsAsset;

ReviewsAsset::register($this);

/* @var $this yii\web\View */
/* @var $searchModel hrupin\reviews\models\Reviews */

$this->title = Yii::t('reviews', 'Reviews');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-index">
    <?php
        foreach($types as $key => $value){
            echo '<a class="btn btn-default" href="'.Url::toRoute(['index', 'type' => $key]).'">'.$key;
            if($value){
                echo ' <span class="countReviewsInBlock">'.$value.'</span>';
            }
            echo '</a> ';
        }
    ?>
    <hr>
    <div class="blog-reviews">
        <?php
            $model = new \hrupin\reviews\models\Reviews();
            foreach ($pageAndType as $value){
                $c = $model->find()->getNoActiveReviewsForPageAndType($value->page, $value->type)->count();
                echo '<a class="btn btn-default" href="'.Url::toRoute(['view-review', 'page' => $value->page, 'type' => $value->type]).'">'.$value->page;
                if($c){
                    echo ' <span class="countReviewsInBlock">'.$c.'</span>';
                }
                echo '</a> ';
            }
        ?>
    </div>
</div>