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

```php
<?= \hrupin\reviews\AutoloadExample::widget(); ?>
```

### configure

in **common/config/main.php**

```
'modules' => [
        'reviews' => [
            'class' => 'hrupin\reviews\Module',
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

### migrate

```
 php yii migrate/up --migrationPath=@vendor/hrupin/yii2-reviews/migrations
 ```
