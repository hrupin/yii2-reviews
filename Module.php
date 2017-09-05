<?php
namespace hrupin\reviews;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const VERSION = '0.0.1';
    public $mailer = [];
    public $modelMap = [];
    public $userModel;
    public $urlPrefix = 'reviews';
    public $debug = false;
    public $ratingStars;
    public $moderateReviews = true;
    public $customOptions = ['reviews' => []];

}
