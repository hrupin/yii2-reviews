<?php
namespace hrupin\reviews\filters;

use yii\web\NotFoundHttpException;
use yii\base\ActionFilter;

class BackendFilter extends ActionFilter
{

    public $controllers = [];

    public function beforeAction($action)
    {
        if (in_array($action->controller->id, $this->controllers)) {
            throw new NotFoundHttpException('Not found');
        }
        return true;
    }
}