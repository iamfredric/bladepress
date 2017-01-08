# Bladepress

Bladepress is a simple wrapper to get laravel blade templates to work with your wordpress theme.
It's built around [duncan3dc/blade](https://github.com/duncan3dc/blade).

### Usage
```
composer require iamfredric/bladepress
```

In your project (for example in your functions.php)
```php
require 'vendor/autoload.php';

Bladepress\Engine::start(
    // First argument is the path to your blade views folder
    '/path/to/blade/views',

    // Second argument is the path to your cache folder
    '/cache/path',

    // Third argument is optional and should contain your view composers
    ['key' => 'Callable composer'],

    // Fourth argument is optional and should contain your cusom
    // blade directives
    ['array', 'of', 'blade', 'directives'] // Optional
);
```

### License
[MIT](https://opensource.org/licenses/MIT)

