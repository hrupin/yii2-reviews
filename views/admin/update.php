<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */

$this->title = Yii::t('reviews', 'Update {modelClass}: ', [
    'modelClass' => 'Reviews',
]) . $model->reviews_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('reviews', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->reviews_id, 'url' => ['view', 'id' => $model->reviews_id]];
$this->params['breadcrumbs'][] = Yii::t('reviews', 'Update');
?>
<div class="reviews-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
