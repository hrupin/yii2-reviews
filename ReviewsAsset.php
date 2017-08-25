<?php

namespace hrupin\reviews;

use yii\web\AssetBundle;
/**
 * Class ReviewsAsset
 *
 * @package yii2mod\comments
 */
class ReviewsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/hrupin/yii2-reviews/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/reviews.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/reviews.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}