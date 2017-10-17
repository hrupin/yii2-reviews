<?php
/**
 * Created by PhpStorm.
 * User: hrupin
 * Date: 25.08.17
 * Time: 19:22
 */
switch($result){
    case 'success':
        if(Yii::$app->getModule('reviews')->moderateReviews){
            echo "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Успешно!</strong> Feedback successfully sent to moderation.')."</div>";
        }
        else{
            echo "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Успешно!</strong> Your review add.')."</div>";
        }
        break;
    case 'error':
        foreach ($error as $item) {
            echo "<div class='alert alert-danger'>".Yii::t('reviews', '<strong>Ошибка!</strong> '.$item[0].'.')."</div>";
        }
        break;
}