<?php

namespace hrupin\reviews\models;

/**
 * This is the ActiveQuery class for [[Reviews]].
 *
 * @see Reviews
 */
class ReviewsQuery extends \yii\db\ActiveQuery
{

    public function getReviewsForPage($id)
    {
        return $this->andWhere('[[page]]='.$id);
    }

    public function getActiveReviewsForPage($id)
    {
        return $this->andWhere("[[page]]='".$id."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->andWhere('[[level]]=1');
    }

    public function getActiveReviewsForPageAndMainLevel($id)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]='".$id."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->orderBy(['reviews_id' => SORT_DESC]);
    }

    public function getParentReviews($id){
        return $this->andWhere('[[reviews_id]]='.$id)->limit(1);
    }

    public function getActive()
    {
        return $this->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE);
    }

    /**
     * @inheritdoc
     * @return Reviews[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Reviews|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}