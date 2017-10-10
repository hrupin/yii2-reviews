Reviews for YII2
================
Module Reviews for YII2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Need RBAC

Either run

```
php composer.phar require --prefer-dist hrupin/yii2-reviews "*"
```

or add

```
"hrupin/yii2-reviews": "*"
```

to the require section of your `composer.json` file.


### configure

in **common/config/main.php**

```
'modules' => [
        'reviews' => [
            'class' => 'hrupin\reviews\Module',
            'userModel' => 'common\models\User',
            'modelMap' => [
                'Reviews' => 'common\models\Reviews',
            ],
            'controllerMap' => [
                'admin' => 'backend\controllers\ReviewsController',
                'reviews' => 'frontend\controllers\ReviewsController'
            ],
            'moderateReviews' => false,
            'ratingStars' => [
                1 => 'Ужасно',
                2 => 'Плохо',
                3 => 'Нормально',
                4 => 'Хорошо',
                5 => 'Отлично'
            ],
            'customOptions' => [
                'Company' => [
                    [
                        'type'  => 'radioList',
                        'statistic' => [
                            'bad' => ['1'],
                            'good' => ['3'],
                        ],
                        'data'  => ['3' => 'Да', '1' => 'Нет', '2' => 'Не помню'],
                        'label' => 'Цены и наличие были указаны верно?',
                        'answer' => [
                            1 => 'Цена и наличие не соответствовали',
                            2 => 'Не помню',
                            3 => 'Цена и наличие были указаны верно'
                        ]
                    ],
                    [
                        'type'  => 'radioList',
                        'statistic' => [
                            'bad' => ['1'],
                            'good' => ['3'],
                        ],
                        'data'  => ['3' => 'Да', '1' => 'Нет', '2' => 'Не помню'],
                        'label' => 'Заказ был вополнен в оговоренные сроки?',
                        'answer' => [
                            1 => 'При выполнении заказа была задержка',
                            2 => 'Не помню',
                            3 => 'Заказ был вополнен в оговоренные сроки'
                        ]
                    ],
                    [
                        'type'   => 'dropDownList',
                        'statistic' => [
                            'bad' => ['1','2','3','4','5'],
                            'good' => ['6'],
                        ],
                        'data'   => [
                            '6'=>'В течение 30 минут',
                            '5' => 'В течение двух часов',
                            '4' => 'В течение дня',
                            '3' => 'На следующий день',
                            '2' => 'Не связались',
                            '1' => 'Я звонил сам',
                        ],
                        'params' => ['prompt' => 'Как быстро с вами связались после заказа?'],
                        'label' => 'Как быстро с вами связались после заказа?',
                        'answer' => [
                            1 => 'Я звонил сам',
                            2 => 'Не связались',
                            3 => 'На следующий день',
                            4 => 'В течение дня',
                            5 => 'В течение двух часов',
                            6 =>'В течение 30 минут',
                        ]
                    ]
                ],
                'User' => [
                    [
                        'type'  => 'radioList',
                        'data'  => ['3' => 'Да', '1' => 'Нет', '2' => 'Не помню'],
                        'label' => 'Соответствовал ли товар описанию и состоянию указаные в описании продавца?',
                        'answer' => [
                            1 => 'Описание товара не соответствовало',
                            2 => 'Не помню',
                            3 => 'Описание товара полностью соответствовали'
                        ]
                    ],
                    [
                        'type'  => 'radioList',
                        'data'  => ['3' => 'Да', '1' => 'Нет', '2' => 'Не помню'],
                        'label' => 'Возникали сложности с оплатой и получением?',
                        'answer' => [
                            1 => 'Были сложности с оплатой и получением',
                            2 => 'Не помню',
                            3 => 'Сложности при оплате и получении не возникли'
                        ]
                    ],
                    [
                        'type'  => 'radioList',
                        'data'  => ['3' => 'Да', '1' => 'Нет', '2' => 'Не уверен'],
                        'label' => 'Рекомендуете ли Вы продавца?',
                        'answer' => [
                            1 => 'Рекомендую',
                            2 => 'Воздержусь',
                            3 => 'Не рекомендую'
                        ]
                    ]
                ],
                'Project' => []
            ]
        ],
```

in **frontend/config/main.php**

```
'modules' => [
    'reviews' => [
        'as frontend' => 'hrupin\reviews\filters\FrontendFilter',
    ],
]
```

in **backend/config/main.php**

```
'modules' => [
    'reviews' => [
        'as backend' => 'hrupin\reviews\filters\BackendFilter',
    ],
],
```

add **to model User**
```
    public function getPublicAvatar()
    {
        // your code
    }

    public function getPublicName()
    {
        // your code
    }
    
    public function getSendEmail()
    {
        // your code
        // return true or false
    }

```

### migrate

```
 php yii migrate/up --migrationPath=@vendor/hrupin/yii2-reviews/migrations
 ```
 
delete
```
php yii migrate/down --migrationPath=@vendor/hrupin/yii2-reviews/migrations
```

Usage
-----

Once the extension is installed, simply use it in your code by  :

MINIMUM
```
<?= hrupin\reviews\widgets\Reviews::widget([
    'pageIdentifier' => 'p_11'
]); ?>
```

FULL
```
<?= hrupin\reviews\widgets\Reviews::widget([
    'reviewsIdentifier' => 'categoryId',
    'pageIdentifier' => 'p_11',
    'reviewsView' => '/ad/reviews',
    'enableReviews' => true,
    'emailAuthor' => 'info@yandex.ru'
]); ?>
```

AND
```

<?= \hrupin\reviews\widgets\ReviewsModal::widget([
    'pageIdentifier' => 'index'
]); ?>

<?= hrupin\reviews\widgets\ReviewsStatistics::widget([
    'pageIdentifier' => ['index', 'index-2'],
    'reviewsIdentifier' => 'index',
    'statisticsReviews' => [
            ['name' => 'Отрицательные', 'check' => 2], // 1 and 2 stars
            ['name' => 'Нейтральные',   'check' => 3], // 3 stars
            ['name' => 'Положительные', 'check' => 5] // 4 and 5 stars
    ],
    'timePeriod' => [
        'type' => 'month', // day, month, year
        'period' => [1, 3, 6],
        'name' => ['месяц', 'месяца', 'месяцев']
    ]
]);
?>

<?= \hrupin\reviews\widgets\CustomerRating::widget([
    'pageIdentifier' => ['index'],
    'reviewsIdentifier' => 'reviews',
]); ?>


<?= \hrupin\reviews\widgets\ReviewsList::widget([
    'pageIdentifier' => ['index'],
]); ?>

<?php
$m = \hrupin\reviews\models\Reviews::getSecondaryPositiveNumber(['index'],'reviews');
var_dump($m);

array(2) {
    ["rating"] = float(25)
    ["count"] = string(1) "8"
}

$n = \hrupin\reviews\models\Reviews::getNewReviews();
echo 'new reviews: ' . $n;

new reviews: 2

?>
```
