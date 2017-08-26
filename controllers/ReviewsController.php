<?php

namespace hrupin\reviews\controllers;

use Yii;
use hrupin\reviews\models\Reviews as ModelReviews;

class ReviewsController extends \yii\web\Controller
{

    public function actionCreateReview(){
        if(Yii::$app->request->isAjax){
            $model = Yii::createObject(ModelReviews::className());
            if(Yii::$app->request->isPost){
                if($model->load(Yii::$app->request->post())){
                    $model->user_id = Yii::$app->user->id;
                    $model->dataAr = $model->data;
                    $model->status = (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1;
                    if($model->save()){
                        return $this->renderAjax('response', [
                            'result' => 'success'
                        ]);
                    }
                }
            }
            return $this->renderAjax('response', [
                'result' => 'error'
            ]);
        }
        else{
            echo "This url only AJAX!!!";
        }
    }

    public function actionCreateResponse(){
        if(Yii::$app->request->isAjax){
            $model = Yii::createObject(ModelReviews::className());
            if(Yii::$app->request->isPost){
                if(
                    Yii::$app->request->post('reviews_id') &&
                    Yii::$app->request->post('text')
                ){
                    $id = Yii::$app->request->post('reviews_id');
                    $model->text = Yii::$app->request->post('text');
                    if($parentReviews = ModelReviews::find()->getParentReviews($id)->one()){
                        $model->page = $parentReviews->page;
                        $model->type = $parentReviews->type;
                        $model->rating = (int)$parentReviews->rating;
                        $model->reviews_parent = $id;
                        $model->level = ((int)$parentReviews->level) + 1;
                        $model->user_id = Yii::$app->user->id;
                        $model->status = (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1;
                        $model->dataAr = [];
                        if($model->save()){
                            $parentReviews->reviews_child = 1;
                            $parentReviews->update();
                            $res = [
                                'status' => 'success',
                                'reload' => (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1,
                                'message' => "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Success!</strong> Feedback successfully sent to moderation.')."</div>"
                            ];
                            return json_encode($res);
                        }
                    }
                }
            }
            $res = [
                'status' => 'error',
                'message' => "<div class='alert alert-danger'>".Yii::t('reviews', '<strong>Error!</strong> The opinion was not sent! Repeat again after some time.')."</div>"
            ];
            return json_encode($res);
        }
        else{
            echo "This url only AJAX!!!";
        }
    }

    public function actionUpdateReview(){
//        if(Yii::$app->request->isAjax){
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return ['answer' => '200', 'result' => Category::getChild(Yii::$app->request->post('url'))];
//        }
    }

    public function actionDeleteReview(){
//        if(Yii::$app->request->isAjax){
//            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return ['answer' => '200', 'result' => Category::getChild(Yii::$app->request->post('url'))];
//        }
    }

}