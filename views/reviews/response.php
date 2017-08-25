<?php
/**
 * Created by PhpStorm.
 * User: hrupin
 * Date: 25.08.17
 * Time: 19:22
 */
switch($result){
    case 'success':
        echo "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Success!</strong> Feedback successfully sent to moderation.')."</div>";
        break;
    case 'error':
        echo "<div class='alert alert-danger'>".Yii::t('reviews', '<strong>Error!</strong> The opinion was not sent! Repeat again after some time.')."</div>";
        break;
}