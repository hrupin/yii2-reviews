<?php

namespace hrupin\reviews\widgets;

use Yii;
use yii\base\Widget;
use hrupin\reviews\ReviewsAsset;
use hrupin\reviews\models\Reviews as ModelReviews;
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

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'reviews';
        }

        if ($this->customOptions === null) {
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
        $this->registerAssets();
        $model = Yii::createObject(ModelReviews::className());
        $model->rating = $model->getAverageNumberStars($this->pageIdentifier);
        $attributes = [
            'page' => $this->pageIdentifier,
            'type' => $this->reviewsIdentifier
        ];
        $model->attributes = $attributes;
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                $model->dataAr = $model->data;
                if($model->save()){
                    echo 1;
                }
                else{
                    var_dump($model->getErrors());
                }
            }
        }
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