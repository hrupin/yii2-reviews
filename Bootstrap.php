<?php
namespace hrupin\reviews;

use Yii;
use yii\i18n\PhpMessageSource;
use yii\base\BootstrapInterface;

/**
 * Blogs module bootstrap class.
 */
class Bootstrap implements BootstrapInterface
{
    /** @var array Model's map */
    private $_modelMap = [
        'Reviews'         => 'hrupin\reviews\models\Reviews',
        'ReviewsQuery'    => 'hrupin\reviews\models\ReviewsQuery',
        'ReviewsSearch'   => 'hrupin\reviews\models\ReviewsSearch',

    ];
    
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        if ($app->hasModule('reviews') && ($module = $app->getModule('reviews')) instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            foreach ($this->_modelMap as $name => $definition) {
                $class = "hrupin\\reviews\\models\\" . $name;
                Yii::$container->set($class, $definition);
                $modelName = is_array($definition) ? $definition['class'] : $definition;
                $module->modelMap[$name] = $modelName;
            }
            if (!isset($app->get('i18n')->translations['reviews'])) {
                $app->get('i18n')->translations['reviews*'] = [
                    'class' => PhpMessageSource::className(),
                    'basePath' => __DIR__ . '/messages',
                    'fileMap' => [
                        'reviews'       => 'reviews.php',
                    ],
                    'sourceLanguage' => 'en-US'
                ];
            }
            Yii::$container->set('hrupin\reviews\Mailer', $module->mailer);
            $module->debug = $this->ensureCorrectDebugSetting();
        }
    }
    
    public function ensureCorrectDebugSetting()
    {
        if (!defined('YII_DEBUG')) {
            return false;
        }
        if (!defined('YII_ENV')) {
            return false;
        }
        if (defined('YII_ENV') && YII_ENV !== 'dev') {
            return false;
        }
        if (defined('YII_DEBUG') && YII_DEBUG !== true) {
            return false;
        }
        return Yii::$app->getModule('reviews')->debug;
    }
}
