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
     * @var string
     */
    public $pageIdentifier;

    /**
     * @var string
     */
    public $reviewsIdentifier;

    /**
     * @var string $reviewsView - template view
    */
    public $reviewsView;

    /**
     * @var array - custom options for data
     */
    public $customOptions;

    /**
     * @var bool
     */
    public $enableReviews;

    /**
    * @var string
    */
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

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'reviews';
        }

        if ($this->customOptions === null) {
            $this->customOptions = [];
        }

        if ($this->enableReviews === null) {
            $this->enableReviews = true;
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
        $ratingStars = Yii::$app->getModule('reviews')->ratingStars;
        $model = Yii::createObject(ModelReviews::className());
        $model->rating = $model->getAverageNumberStars($this->pageIdentifier, $this->reviewsIdentifier);
        $model->type = $this->reviewsIdentifier;
        $model->page = $this->pageIdentifier;
        $reviews = $model->getReviews(ModelReviews::find()->getActiveReviewsForPageAndMainLevel($this->pageIdentifier, $this->reviewsIdentifier));
        return $this->render($this->reviewsView,[
            'reviews' => $reviews,
            'model' => $model,
            'options' => $this->customOptions,
            'stars' => $ratingStars,
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