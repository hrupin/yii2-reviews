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
     * @var bool
     */
    public $enableReviews = false;
    /**
     * @var bool
     */
    public $fieldsUserModel;

    private $pathIMG;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }

        if ($this->fieldsUserModel === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "fieldsUserModel" property must be set.'));
        }

        if($this->userModel === null){
            $userObjectNamespace = Yii::$app->getModule('reviews')->userModel;
        }
        else{
            $userObjectNamespace = $this->userModel;
        }
        $this->userModel = Yii::createObject($userObjectNamespace::className());

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
            'user_id' => Yii::$app->user->id, // будет ошибка если user не авторизован
            'page' => $this->pageIdentifier,
            'type' => $this->reviewsIdentifier
        ];
        $model->attributes = $attributes;
        if(Yii::$app->request->isPost){
            if($model->load(Yii::$app->request->post())){
                $model->dataAr = $model->data;
                if($model->save()){
                    Yii::$app->session->setFlash('success', Yii::t('reviews', 'Feedback successfully sent to moderation'));
                }
                else{
                    Yii::$app->session->setFlash('error', Yii::t('reviews', 'The opinion was not sent! Repeat again after some time.'));
                }
            }
        }
        $reviews = $model->getReviews(ModelReviews::find()->getActiveReviewsForPageAndMainLevel($this->pageIdentifier)->all());
//        $ratingStatistic =
        return $this->render($this->reviewsView,[
            'reviews' => $reviews,
            'model' => $model,
            'options' => $this->customOptions,
            'stars' => $this->ratingStars,
            'enableReviews' => $this->enableReviews,
            'pathIMG' => $this->pathIMG
        ]);
    }

    /**
     * Register assets.
     */
    protected function registerAssets()
    {
        $view = $this->getView();
        $bundle = ReviewsAsset::register($view);
        ModelReviews::$pathIMG = $bundle->baseUrl;
    }

}