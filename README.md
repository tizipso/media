dcat-admin extension
======

Media manager for dcat-admin
===============================

Media manager for `local` disk.

## Installation

```
$ composer require tizipso/media

$ php artisan admin:import Dcat\Admin\Extension\Media\Media
```

Add a disk config in `config/admin.php`:

```php

    'extensions' => [

        'media' => [
        
            // Select a local disk that you configured in `config/filesystem.php`
            'disk' => 'public'
        ],
    ],

```


Open `http://localhost/admin/media`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
