Blocks
======
Blocks

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Add to your composer.json.

```
"repositories":[
    {
        "type": "git",
        "url": "https://github.com/thefx/yii2-blocks"
    }
]
```

Either run

```
composer require thefx/yii2-blocks:dev-master
```

or add

```
"thefx/yii2-blocks": "dev-master"
```

to the require section of your `composer.json` file.

Alternative installation
---

1. Move libs to extensions/thefx/yii2-blocks
2. then add to your config

```
'aliases' => [
    '@thefx/blocks' => '@app/extensions/thefx/yii2-blocks',
    ...
],
```


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \thefx\blocks\AutoloadExample::widget(); ?>
```


For Development
-----

```
"repositories":[
    {
        "type": "path",
        "url": "extensions/thefx/*"
    }
]
```

Either run

```
composer require thefx/yii2-blocks:dev-master --prefer-source
```

Modify your application configuration:

```
return [
    'modules' => [
        'blocks' => [
            'class' => 'thefx\blocks\Module',
            'layout' => 'page',
            'layoutPath' => '@app/modules/admin/layouts',
            'rootUsers' => [1],
        ...
        ]
        ...
    ],
];
```

Apply Migrations

```
php yii migrate --migrationPath=@thefx/blocks/migrations
```

Refresh Migrations

```
php yii migrate/fresh --migrationPath=@thefx/blocks/migrations
```

Create block

```
http://site.com/blocks/block
```
