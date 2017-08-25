<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model hrupin\reviews\models\Reviews */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="reviews-form">
    <?php $form = ActiveForm::begin(['action' => ['reviews/reviews/create-review'], 'options' => ['data-pjax' => true]]); ?>
    <div id="rating" class="rating">
        <?php
            $e = 1; $count = count($stars);
            for($e; $e <= $model->rating; $e++){
                echo '<span class="glyphicon glyphicon-star" data-text="'.$stars[$e].'" data-rating="'.$e.'" aria-hidden="true"></span>';
            }
            for($e; $e <= $count; $e++){
                echo '<span class="glyphicon glyphicon-star-empty" data-text="'.$stars[$e].'" data-rating="'.$e.'" aria-hidden="true"></span>';
            }
        ?>
    </div>
    <div id="textRating"></div>

    <?= $form->field($model, 'rating')->hiddenInput(['value' => 0])->label(false); ?>

    <?php
    foreach ($options as $key => $option) {
        $type = isset($option['type'])? $option['type']: 'textInput';
        if(is_array($option)){
            $label = isset($option['label'])? $option['label']: $key;
        }
        else{
            $label = $option;
        }
        $data = isset($option['data'])? $option['data']: [];
        $params = isset($option['params'])? $option['params']: [];
        switch($type){
            case 'textInput':
                echo $form->field($model, 'data['.$key.']')->$type($data)->label($label);
                break;
            case 'textarea':
                echo $form->field($model, 'data['.$key.']')->$type($data)->label($label);
                break;
            case 'passwordInput':
                echo $form->field($model, 'data['.$key.']')->$type()->$data($params)->label($label);
                break;
            case 'input':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'fileInput':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'checkbox':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'checkboxList':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'radio':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'radioList':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'listBox':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
            case 'dropDownList':
                echo $form->field($model, 'data['.$key.']')->$type($data, $params)->label($label);
                break;
        }
    }
    ?>
    <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'type')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'page')->hiddenInput()->label(false); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('reviews', 'Create') : Yii::t('reviews', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>