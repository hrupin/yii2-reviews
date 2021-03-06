<?php

namespace hrupin\reviews\models;

/**
 * This is the ActiveQuery class for [[Reviews]].
 *
 * @see Reviews
 */
class ReviewsQuery extends \yii\db\ActiveQuery
{

    public function getReviewsForPage($id, $type)
    {
        return $this->andWhere('[[page]]='.$id)
            ->andWhere("[[type]]='".$type."'");
    }

    public function getActiveReviewsForPage($id, $type)
    {
        return $this->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->andWhere('[[level]]=1');
    }

    public function getNoActiveReviewsForPage($type)
    {
        return $this->andWhere("[[type]]='".$type."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_NOT_ACTIVE);
    }

    public function getNoActiveReviewsForPageAndType($page, $type)
    {
        return $this->andWhere("[[type]]='".$type."'")
            ->andWhere("[[page]]=".$page)
            ->andWhere('[[status]]='.Reviews::REVIEWS_NOT_ACTIVE);
    }

    public function getActiveReviewsForPageAndMainLevel($id, $type)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->all();
    }

    public function getAllReviewsForPageAndMainLevel($id, $type)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere('status <> '.Reviews::REVIEWS_DELETE)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->all();
    }

    public function getActiveReviewsForPageAndMainLevelCount($id, $type)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->count();
    }

    public function getActiveReviewsForPageAndMainLevelForPeriod($id, $type, $period)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere("date_update > ".$period)
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->all();
    }

    public function countActiveReviewsForPageAndMainLevelForRating($id, $type, $rating)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere("[[rating]]=".$rating)
            ->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->count();
    }

    public function countAllReviewsForPageAndMainLevelForRating($id, $type, $rating)
    {
        return $this->andWhere("[[level]]=1")
            ->andWhere("[[page]]=".$id)
            ->andWhere("[[type]]='".$type."'")
            ->andWhere("[[rating]]=".$rating)
            ->orderBy(['reviews_id' => SORT_DESC])
            ->andWhere('status <> '.Reviews::REVIEWS_DELETE)
            ->count();
    }

    public function getReviews($id){
        return $this->andWhere('[[reviews_id]]='.$id)->limit(1)->one();
    }

    public function getActive()
    {
        return $this->andWhere('[[status]]='.Reviews::REVIEWS_ACTIVE);
    }

    public function getNotActive()
    {
        return $this->andWhere('[[status]]='.Reviews::REVIEWS_NOT_ACTIVE);
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
