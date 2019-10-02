Live content extension for Yii 2
=========================

This extension provides easy way to make dynamic content of web application for [Yii framework 2.0](http://www.yiiframework.com) applications.

Installation
------------
The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist sergeykoz/yii2-livecontent:0.1.0
```

or add

```
"sergeykoz/yii2-livecontent": "~0.1.0"
```

to the require section of your `composer.json` file.

Updating database schema
------------------------
After you downloaded `sergeykoz/yii2-livecontent`,
you need to do is updating your database schema by applying the migrations:

In `command line`:
```
php yii migrate --migrationPath=@vendor/sergeykoz/yii2-livecontent/src/migrations
```
or configure `controllerMap` settings

Configuration
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['livecontent'],
    'modules' => [
        'livecontent' => [
            'class' => 'ssoft\livecontent\Module',
            //'accessRules' => [[
            //    'allow' => true,
            //    'verbs' => ['POST']
            //]],
            //'editorOptions' => [
            //    'clientOptions'=>[
            //        'rows' => 6,
            //        'autoParagraph'=>false 
            //    ],
            //    'preset' => 'full',
            //    'autoParagraph' => true
            //]
        ],
        ...
    ],
    ...
];
```

Config file `console.php`

```php
return [
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationNamespaces' => [
                'ssoft\livecontent\migrations'
            ],
        ],
    ],
];
```

Usage
-----
In view
```php
<?php

use ssoft\livecontent\Content;

...

/*
You should replace parts of content which needs to be dynamic with calling of methods:
Content::text
Content::textarea
Content::html
Content::block

This mehtods need two arguments except of Content::block.

place -  uses for identification of the content place. You have to specify a unique key for current view file. You can insert language prefix for multilanguage applications.

isAllow - a flag which allow to show edit element on the page. You can use different cases for the argument. For basic application a case which allow to change content only for logged user is !Yii::$app->user->isGuest. For applications based on RBAC access you can use \Yii::$app->user->can('admin')

blockLogic - the argument uses just for Content::block method which consists HTML template and fields to the template.
*/


echo Content::text('text-id-'.\Yii::$app->language, !Yii::$app->user->isGuest);

...

echo Content::textarea('textblock-id', !Yii::$app->user->isGuest); // allowed to logged user

...

echo Content::html('formatted-text-id', \Yii::$app->user->can('admin')); // allowed for RBAC role  admin

...

$block=[
    'template' => "<div class=\"col-lg-4\">{edit}
        <h2>{head}</h2>
        {description}
        {show_link on}<p><a class=\"btn {button}\" href=\"{link is null}#{/link is null}{link is not null}{link}{/link is not null}\">{caption}</a></p>{/show_link on}
    </div>",               
    'rules'=> [
        'edit'=>['type'=>'editcontrol',],              
        'head' => ['name' => 'Title', 'type' => 'text', 'required' => true],
        'description' => ['name' => 'Description', 'type' => 'html', 'required' => true],
        'caption' => ['name' => 'Text', 'type'=>'text', 'required' => true],
        'link' => ['name' => 'Link','type'=>'text'],
        'show_link' => ['name' => 'Show a button','type'=>'checkbox', 'required' => true],
        'button' => [
            'name' => 'Button type',
            'type' => 'select',
            'options'=>[
                ['option'=>'Default', 'value' => 'btn-default'],
                ['option'=>'Primary', 'value' => 'btn-primary'],
                ['option'=>'Success', 'value' => 'btn-success'],
                ['option'=>'Info', 'value' => 'btn-info'],
                ['option'=>'Warning', 'value' => 'btn-warning'],
                ['option'=>'Danger', 'value' => 'btn-danger'],
            ],
            'required' => true
        ],
    ]
];
echo Content::block('block-id', !Yii::$app->user->isGuest, $block);  
?>