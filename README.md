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
php composer.phar require thefx/yii2-blocks:dev-master
```

or add

```
"thefx/yii2-blocks": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
<?= \thefx\blocks\AutoloadExample::widget(); ?>
```

For Development
-----

Add to your composer.json.

```
"autoload": {
  "psr-4": {
    "thefx\\blocks\\": "extensions/thefx/yii2-blocks/"
  }
},
```

Modify your application configuration:

```
return [
    'aliases' => [
        '@thefx/blocks' => '@app/extensions/thefx/yii2-blocks',
        ...
    ],
    'modules' => [
        'blocks-manage' => [
            'class' => 'thefx\blocks\Module',
            'layout' => 'page',
            'layoutPath' => '@app/modules/admin/layouts',
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

