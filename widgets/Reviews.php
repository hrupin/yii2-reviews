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
     * @var string $emailAuthor - e-mail page's author
    */
    public $emailAuthor;

    /**
     * @var array - custom options for data
     */
    public $customOptions;

    /**
     * @var bool
     */
    public $enableReviews;
    
    /**
    * @var boolean
    */
    public $allowResponse;

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
        
        if ($this->allowResponse === null) {
            $this->allowResponse = true;
        }

        if ($this->enableReviews === null) {
            $this->enableReviews = true;
        }

        if ($this->emailAuthor === null) {
            $this->emailAuthor = false;
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
        $reviews = $model->getReviews($model->find()->getActiveReviewsForPageAndMainLevel($this->pageIdentifier, $this->reviewsIdentifier));
        $userCountReviews = 0;
        if(!Yii::$app->user->isGuest){
            $userCountReviews = \hrupin\reviews\models\Reviews::find()->where(['user_id' => Yii::$app->user->id])->andWhere('date_create > '.mktime(0,0,0))->count();
        }
        return $this->render($this->reviewsView,[
            'reviews' => $reviews,
            'model' => $model,
            'options' => Yii::$app->getModule('reviews')->customOptions[$this->reviewsIdentifier],
            'stars' => $ratingStars,
            'enableReviews' => $this->enableReviews,
            'pathIMG' => $this->pathIMG,
            'emailAuthor' => $this->emailAuthor,
            'userCountReviews' => $userCountReviews,
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
