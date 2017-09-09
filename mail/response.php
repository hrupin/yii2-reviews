<?php

/* @var $this \yii\web\View view component instance */
/* @var $message \yii\mail\BaseMessage instance of newly created mail message */

?>
    <h2><?= Yii::t('reviews', 'Send new response'); ?></h2>
<?= Yii::t('reviews', 'Was written the answer to your review on the page {url}', [
    'url' => $url
]); ?>