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
class ReviewsList extends Widget
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
    public $pathIMG;
    
    
    /**
     * @var string
     */
    public $allowResponse;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }
        
        if (!is_array($this->pageIdentifier)) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be array.'));
        }

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'reviews-list';
        }
        
        if ($this->allowResponse === null) {
            $this->allowResponse = true;
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
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        $model->rating = $model->getAverageNumberStars($this->pageIdentifier, $this->reviewsIdentifier);
        $model->type = $this->reviewsIdentifier;
        $model->page = $this->pageIdentifier;        
        $tmp = [];
        foreach($this->pageIdentifier as $item){
            $tmp = ModelReviews::arraySum($tmp, $model->find()->getActiveReviewsForPageAndMainLevel($item, $this->reviewsIdentifier));
        }
        $reviews = $model->getReviews($tmp);        
        if(!count($reviews)){
            return false;
        }
        return $this->render($this->reviewsView,[
            'reviews' => $reviews,
            'model' => $model,
            'pathIMG' => $this->pathIMG,
            'stars' => $ratingStars,
            'options' => Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier],
            'allowResponse' => $this->allowResponse
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
