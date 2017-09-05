<?php

namespace hrupin\reviews\widgets;

use Yii;
use yii\base\Widget;
use hrupin\reviews\models\Reviews as ModelReviews;
use yii\base\InvalidConfigException;

/**
 * Class Reviews
 *
 * @package hrupin\reviews\widgets
 */
class ReviewsStatistics extends Widget
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
     * @var array
     */
    public $timePeriod;

    /**
     * @var array
     */
    public $statisticsReviews;

    /**
     * Initializes the widget params.
     */
    public function init()
    {
        parent::init();

        if ($this->pageIdentifier === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "pageIdentifier" property must be set.'));
        }

        if ($this->statisticsReviews === null) {
            throw new InvalidConfigException(Yii::t('reviews', 'The "statisticsReviews" property must be set.'));
        }

        if($this->reviewsIdentifier === null){
            $this->reviewsIdentifier = 'reviews';
        }

        if($this->timePeriod === null){
            $this->timePeriod = false;
        }

        if ($this->reviewsView === null) {
            $this->reviewsView = 'statistics';
        }

    }

    /**
     * Executes the widget.
     *
     * @return string the result of widget execution to be outputted
     */
    public function run()
    {
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        if($this->timePeriod){
            $step = 0;
            switch(true){
                case 'year':
                    $step = 31536000;
                    break;
                case 'month':
                    $step = 2592000;
                    break;
                case 'day':
                    $step = 86400;
                    break;
            }
            $statistics = [];
            foreach ($this->timePeriod['period'] as $k=>$value){
                $statistics[$value . ' ' . $this->timePeriod['name'][$k]] = $model->getStatistics(
                    ModelReviews::find()->getActiveReviewsForPageAndMainLevelForPeriod(
                        $this->pageIdentifier,
                        $this->reviewsIdentifier,
                        (time() - ($step*$value))
                    ),
                    $this->statisticsReviews
                );
            }
        }
        else{
            $statistics = $model->getStatistics(
                ModelReviews::find()->getActiveReviewsForPageAndMainLevel(
                    $this->pageIdentifier,
                    $this->reviewsIdentifier
                ),
                $this->statisticsReviews
            );
        }
        return $this->render($this->reviewsView,[
            'model' => $statistics,
            'statistics' => $this->statisticsReviews
        ]);
    }

}