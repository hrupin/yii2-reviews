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


Usage
-----

Once the extension is installed, simply use it in your code by  :

MINIMUM
```
<?= Reviews::widget([
    'pageIdentifier' => 'p_11'
]); ?>
```

FULL
```
<?= Reviews::widget([
    'reviewsIdentifier' => 'categoryId',
    'pageIdentifier' => 'p_11',
    'reviewsView' => '/ad/reviews',
    'enableReviews' => true,
    'ratingStars' => [
        1 => 'Ужасно',
        2 => 'Плохо',
        3 => 'Нормально',
        4 => 'Хорошо',
        5 => 'Отлично'
    ],
    'customOptions' => [
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
]); ?>
```

### configure

in **common/config/main.php**

```
'modules' => [
        'reviews' => [
            'class' => 'hrupin\reviews\Module'
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
    public function getAvatarUser()
    {
        // your code
    }

    public function getNameUser()
    {
        // your code
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