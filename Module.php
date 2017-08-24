<?php
namespace hrupin\reviews;

use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    const VERSION = '0.0.1';
    public $mailer = [];
    public $lang = [];
    public $modelMap = [];
    public $urlPrefix = 'reviews';
    public $debug = false;
    public $userModel = false;
    public $fieldsUserModel = [];
}