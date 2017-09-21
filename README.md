Reviews for YII2
================
Reviews for YII2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

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
        'moderateReviews' => false,
        'admin' => [1,2],
        'ratingStars' => [
           1 => 'Ужасно',
           2 => 'Плохо',
           3 => 'Нормально',
           4 => 'Хорошо',
           5 => 'Отлично'
        ],
        'customOptions' => [
            'reviews' => [
                'listBox'   => [
                    'type'   => 'listBox',
                    'data'   => [1 => '1', 2 => '2', 3 => 3, 4 => 4, 5 => 5],
                    'params' => ['multiple' => true, 'prompt' => 'Выберите один или несколько вариантов','style' => 'background:gray;color:#fff;']
                ],
                'radioList' => [
                    'type'  => 'radioList',
                    'data'  => ['1' => 'Первый', '2' => 'Второй', '3' => 'Третий'],
                    'label' => 'radioList'
                ],
            ],
            'anothePage' => [
                'textInput' => [
                    'type' => 'textInput',
                    'data' => ['placeholder' => '+7 (920) 707 77 20'],
                    'label'=> 'textInput'
                ],
                'dropDownList'   => [
                    'type'   => 'dropDownList',
                    'data'   => ['0' => 'Активный','1' => 'Отключен','2'=>'Удален'],
                    'params' => ['prompt' => 'Выберите статус...']
                ],
                'radio' => [
                    'type'  => 'radio',
                    'data'  => ['label' => 'Радио кнопка','labelOptions' => ['style' => 'padding-left:20px;']],
                    'label' => 'radio'
                ],
                'checkboxList' => [
                    'type'  => 'checkboxList',
                    'data'  => ['a' => 'Элемент А', 'б' => 'Элемент Б', 'в' => 'Элемент В'],
                    'label' => 'checkboxList'
                ],
                'checkbox' => [
                    'type'  => 'checkbox',
                    'data'  => ['label' => 'Неактивный чекбокс', 'labelOptions' => ['style' => 'padding-left:20px;'], 'disabled' => true],
                    'params'=> [],
                    'label' => 'checkbox'
                ],
                'fileInput' => [
                    'type'  => 'fileInput',
                    'data'  => ['multiple' => 'multiple'],
                    'params'=> [],
                    'label' => 'fileInput'
                ],
                'input' => [
                    'type'  => 'input',
                    'data'  => 'email',
                    'params'=> [],
                    'label' => 'input'
                ],
                'passwordInput' => [
                    'type'  => 'passwordInput',
                    'data'  => 'hint',
                    'params'=> 'Длинна пароля не меньше 10 символов.',
                    'label' => 'passwordInput'
                ],
                'textarea' => [
                    'type'  => 'textarea',
                    'data'  => ['rows' => 2, 'cols' => 5],
                    'params'=> [],
                    'label' => 'textarea'
                ],
                'empty'
            ]
        ]
    ],
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
