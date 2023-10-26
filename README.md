Blocks
======

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

```
composer require thefx/yii2-blocks:v2.x-dev
```

or add

```
"thefx/yii2-blocks":  "v2.x-dev"
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

Example config
```
return [
    'block' => [
        'btn_add_group' => true, // show add_group btn
    ],
    'block1' => [
        'btn_add_group' => false,
    ],
    'blockSection' => [
        'anons_redactor' => true,
        'text_redactor' => true,
        'photo_preview' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [1920, 0, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
        'photo' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [1920, 0, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
    ],
    'blockItem' => [
        'anons_redactor' => true,
        'text_redactor' => true,
        'photo_preview' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [1920, 0, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
        'photo' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [1920, 0, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
    ],
    // Where 2 - Block ID
    'blockItem2' => [
        'anons_redactor' => false,
        'text_redactor' => false,
        'photo_preview' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [0, 600, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
        'photo' => [
            'dir' => '@webroot/upload/blocks/',
            'urlDir' => '/upload/blocks/',
            'defaultCrop' => [1920, 0, 'fit'],
            'crop' => [
                [200, 0, 'min', 'fit'], // admin preview
            ]
        ],
    ],
    'blockProperty' => [
        'dir' => '@webroot/upload/blocks/',
        'urlDir' => '/upload/blocks/',
        'resizeQuality' => 90,
        'crop' => [
            [1280, 785, ''],
            [247, 247, 'list_'],
            [150, 150, 'prev_'],
        ]
    ],
    // Where 5 - Property ID
    'blockProperty5' => [
        'dir' => '@webroot/upload/blocks/',
        'urlDir' => '/upload/blocks/',
        'resizeQuality' => 90,
        'crop' => [
            [1280, 0, ''],
            [247, 0, 'list_'],
            [150, 0, 'prev_'],
        ]
    ],
];
```

Usage
-----

Create block

```
http://site.com/blocks/block
```
