<?php

use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */

$this->title = Yii::t('reviews', 'Update {modelClass}: ', [
    'modelClass' => 'Reviews',
]) . $model->reviews_id;

$this->params['breadcrumbs'][] = ['label' => Yii::t('reviews', 'Reviews'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $model->type;
$this->params['breadcrumbs'][] = $model->page;

?>
<div class="reviews-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <a href="<?= Url::toRoute($model->page); ?>"><?= Yii::t('reviews', 'View page'); ?></a>

    <?= $this->render('_form', [
        'model' => $model,
        'pathIMG' => '',
        'stars' => $stars,
        'options' => $options
    ]) ?>

</div>