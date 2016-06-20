Live content extension for Yii 2
=========================

This extension provides easy editing of content for [Yii framework 2.0](http://www.yiiframework.com) applications.

For license information check the [LICENSE](LICENSE.md)-file.

Documentation is at [docs/guide/README.md](docs/guide/README.md).

[![Latest Stable Version](https://poser.pugx.org/yiisoft/yii2-debug/v/stable.png)](https://packagist.org/packages/yiisoft/yii2-debug)
[![Total Downloads](https://poser.pugx.org/yiisoft/yii2-debug/downloads.png)](https://packagist.org/packages/yiisoft/yii2-debug)


Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist yiisoft/yii2-debug
```

or add

```
"sergeykoz/yii2-livecontent": "~0.0.1"
```

to the require section of your `composer.json` file.

Updating database schema
------------------------
After you downloaded and configured `rmrevin/yii2-comments`,
the last thing you need to do is updating your database schema by applying the migrations:

In `command line`:
```
php yii migrate/up --migrationPath=@vendor/rmrevin/yii2-comments/migrations/

Configuration
-----

Once the extension is installed, simply modify your application configuration as follows:

```php
return [
    'bootstrap' => ['livecontent'],
    'modules' => [
        'modules' => [
        'livecontent' => [
            'class' => 'ssoft\livecontent\Module',
            //'accessRules' => [[
            //    'allow' => true,
            //    'roles' => ['admin'],
            //    'verbs' => ['POST']
            //]],
            //'editorOptions' => [
            //    'clientOptions'=>[
            //        'rows' => 6,
            //        'autoParagraph'=>false 
            //    ],
            //    'preset' => 'full'
            //]
        ],
    ],
        // ...
    ],
    ...
];
```

Usage
-----
In view
```php
<?php
// ...

use ssoft\livecontent\Content;

...

echo Content::text('text-id-'.\Yii::$app->language, !Yii::$app->user->isGuest);

...

echo Content::textarea('textblock-id', !Yii::$app->user->isGuest);

...

echo Content::html('formatted-text-id', \Yii::$app->user->can('admin'));

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
echo Content::block('block-id', \Yii::$app->user->can('admin'), $block);  

```