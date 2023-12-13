# SprintFlow

Laravel Package for speed up your project development

## Installation

You can install the package via composer:

```bash
composer require ades4827/sprintflow
```

For customization, you can publish with:

```bash
php artisan vendor:publish --tag=sprintflow-config
php artisan vendor:publish --tag=sprintflow-views
php artisan vendor:publish --tag=sprintflow-lang
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Features

- [Model utils](src/Traits/BaseModelTrait.php)
- Ready to use [Casts](src/Casts)
- API generator for WireUi select [See usage example](examples/API/README.md)
- Permission management [See usage example](examples/Permission/README.md)
- Crud system
- Datatable extension

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email michele@lucchina.com instead of using the issue tracker.

## Credits

-   [Michele Lucchina](https://github.com/ades4827)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
