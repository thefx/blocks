Blocks
======

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require thefx/yii2-blocks:dev-master
```

or add

```
"thefx/yii2-blocks": "dev-master"
```

to the require section of your `composer.json` file.

Apply Migrations
------------

```
php yii migrate --migrationPath=@thefx/blocks/migrations
```

Modify your application configuration:

```
return [
    'modules' => [
        'blocks' => [
            'class' => 'thefx\blocks\Module',
            'layoutPath' => '@app/modules/admin/layouts',
            'layout' => 'page',
            'layoutPure' => 'pure',
            'rootUsers' => [1],
        ...
        ]
        ...
    ],
];
```

Add access only for auth users
```
    'as access blocks' => [
        'class' => 'yii\filters\AccessControl',
        'only' => ['pages/*', 'blocks/*'],
        'rules' => [
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
```

Usage
-----

Create block

```
http://site.com/blocks/block
```
