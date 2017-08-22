<?php

namespace hrupin\reviews\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use hrupin\reviews\ReviewsAsset;
use yii\base\InvalidConfigException;

/**
* Class Reviews
*
* @package hrupin\reviews\widgets
*/
class Reviews extends Widget
{
    /**
     * @var object User
     */
    public $userModel = false;

    /**
     * @var string
     */
    public $pageIdentifier = '';

    /**
     * @var string
     */
    public $reviewsIdentifier = '';

    /**
     * @var string $reviewsView - template view
    */
    public $reviewsView;

    /**
     * @var array - custom options for data
     */
    public $customOptions = [];

    /**
     * @var array - custom options for data
     */
    public $ratingStars = [];

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();
        if($this->userModel === null){
            throw new InvalidConfigException(Yii::t('reviews', 'The "user" property must be set.'));
        }
        if($this->reviewsIdentifier === null){
            throw new InvalidConfigException(Yii::t('reviews', 'The "reviewsIdentifier" property must be set.'));
        }
        if (empty($this->reviewsView)) {
            $this->reviewsView = 'reviews';
        }
        if (empty($this->pageIdentifier)) {
            $this->pageIdentifier = 'reviews';
        }
        if (empty($this->customOptions)) {
            $this->customOptions = [];
        }
        if ($this->ratingStars === null) {
            $this->ratingStars = [];
        }

    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted
     */
    public function run()
    {
        $model = Yii::createObject(\hrupin\reviews\models\Reviews::className());

        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                echo 1;
            }
        }

        $this->registerAssets();
        $model->rating = 3;
        return $this->render($this->reviewsView,[
            'model' => $model,
            'options' => $this->customOptions,
            'stars' => $this->ratingStars
        ]);
    }

    /**
     * Register assets.
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        ReviewsAsset::register($view);
    }

}