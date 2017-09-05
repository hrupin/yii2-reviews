<?php

namespace hrupin\reviews\controllers;

use Yii;
use hrupin\reviews\models\Reviews as ModelReviews;

class ReviewsController extends \yii\web\Controller
{

    public function actionCreateReview(){
        if(Yii::$app->request->isAjax){
            $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
            $model = Yii::createObject($class::className());
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
            $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
            $model = Yii::createObject($class::className());
            if(Yii::$app->request->isPost){
                if(
                    Yii::$app->request->post('reviews_id') &&
                    Yii::$app->request->post('text')
                ){
                    $id = Yii::$app->request->post('reviews_id');
                    $model->text = Yii::$app->request->post('text');
                    if($parentReviews = ModelReviews::find()->getReviews($id)){
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
        echo "This url only AJAX!!!";
    }

    public function actionUpdateReview(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
            $model = Yii::createObject($class::className());
            if(Yii::$app->request->isPost){
                if(Yii::$app->request->post('reviews_id')) {
                    $data = $model->find()->getReviews(Yii::$app->request->post('reviews_id'));
                    if(Yii::$app->request->post('text')){
                        $data->text = Yii::$app->request->post('text');
                        $data->update();
                        $res = [
                            'status' => 'success',
                            'reload' => (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1,
                            'message' => "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Success!</strong> Feedback successfully sent to moderation.')."</div>"
                        ];
                        return $res;
                    }
                    if($data->level > 1){
                        return ['response' => $data, 'edit' => 'response'];
                    }
                    else{
                        return ['response' => serialize($data), 'edit' => 'review'];
                    }
                }
            }
            return $this->renderAjax('response', [
                'result' => 'error'
            ]);
        }
        if(Yii::$app->request->post('hiddenData')){
            $data = unserialize(Yii::$app->request->post('hiddenData'));
            $ratingStars = Yii::$app->getModule('reviews')->ratingStars;
            $data->data = $data->dataAr;
            return $this->render('_form',[
                'model' => $data,
                'options' => Yii::$app->getModule('reviews')->customOptions[$data->type],
                'stars' => $ratingStars,
                'url' => Yii::$app->request->post('hiddenURL')
            ]);
        }
        $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
        $model = Yii::createObject($class::className());
        if($model->load(Yii::$app->request->post())){
            $data = ModelReviews::find()->getReviews((int)$_POST['Reviews']['reviews_id']);
            $data->load(Yii::$app->request->post());
            $data->status = (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1;
            $data->dataAr = $data->data;
            if($data->save()){
                $this->redirect(Yii::$app->request->post('hiddenURL'));
            }
            else{
                $model->data = $model->dataAr;
                return $this->render('_form',[
                    'model' => $model,
                    'options' => Yii::$app->getModule('reviews')->customOptions[$model->type],
                    'stars' => Yii::$app->getModule('reviews')->ratingStars,
                    'url' => Yii::$app->request->post('hiddenURL')
                ]);
            }
        }
    }

    public function actionDeleteReview(){
        if(Yii::$app->request->isAjax){
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $class = Yii::$app->getModule('reviews')->modelMap['Reviews'];
            $model = Yii::createObject($class::className());
            if(Yii::$app->request->isPost) {
                if (Yii::$app->request->post('reviews_id')) {
                    if($data = $model->find()->getReviews(Yii::$app->request->post('reviews_id'))){
                        $data->status = 99;
                        if($data->save()){
                            $res = [
                                'status' => 'success',
                                'reload' => (Yii::$app->getModule('reviews')->moderateReviews)? 0: 1,
                                'message' => "<div class='alert alert-success'>".Yii::t('reviews', '<strong>Success!</strong> Opinion deleted successfully.')."</div>"
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
        echo "This url only AJAX!!!";
    }

}