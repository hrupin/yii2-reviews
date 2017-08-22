<?php

/* @var $this yii\web\View */
/* @var $searchModel hrupin\reviews\models\ReviewsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<?= $this->render('_form', [
    'model' => $model,
    'options' => $options,
    'stars' => $stars
]); ?>