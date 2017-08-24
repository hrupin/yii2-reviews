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
        return $this->andWhere("[[page]]='".$id."'")->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE);
    }

    public function getActiveReviewsForPageAndMainLevel($id)
    {
        return $this->andWhere("[[level]]=1")->andWhere("[[page]]='".$id."'")->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE);
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