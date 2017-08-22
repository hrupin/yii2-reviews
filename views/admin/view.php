<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */

$this->title = $model->reviews_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('reviews', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('reviews', 'Update'), ['update', 'id' => $model->reviews_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('reviews', 'Delete'), ['delete', 'id' => $model->reviews_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('reviews', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'reviews_id',
            'page',
            'type',
            'reviews_child',
            'reviews_parent',
            'user_id',
            'level',
            'raiting',
            'data:ntext',
            'text:ntext',
            'date_create',
            'date_update',
        ],
    ]) ?>

</div>
