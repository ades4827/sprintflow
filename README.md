# SprintFlow

Laravel package to speed up your project development: ready-made models, permissions, CRUD, datatables, media handling and Livewire components.

## Version Compatibility

| Laravel | Livewire | SprintFlow |
|:--------|:---------|:-----------|
| 10.x    | 2.x      | 1.x        |
| 10.x    | 3.x      | 2.x        |
| 11.x    | 3.x      | 3.x        |
| 12.x    | 3.x      | 4.x        |
| 12.x    | 4.x      | 5.x        |
| 13.x    | 4.x      | 5.x        |

SprintFlow 5.x requires PHP 8.3+.

## Installation

Install the package via composer:

```bash
composer require ades4827/sprintflow
```

The service provider and the `Sprintflow` facade are auto-discovered.

### Tailwind

The package views use Tailwind classes, so Tailwind must scan them.

Tailwind v4 (in your CSS file):

```css
@source '../../vendor/ades4827/sprintflow/resources/**/*.php';
```

Tailwind v3 (`tailwind.config.js`):

```js
module.exports = {
    content: [
        "./vendor/ades4827/sprintflow/resources/**/*.php",
    ],
}
```

### Publishing

```bash
php artisan vendor:publish --tag=sprintflow-config
php artisan vendor:publish --tag=sprintflow-views
php artisan vendor:publish --tag=sprintflow-lang
```

### UI kits

Views are loaded under the `sprintflow` namespace. Extra view namespaces are registered automatically when the matching UI kit is installed:

| Package          | View namespace      |
|:-----------------|:--------------------|
| `livewire/flux`  | `sprintflow_fluxui` |
| `wireui/wireui`  | `sprintflow_wireui` |

### Optional dependencies

Some features need extra packages. Install only what you use:

| Package                            | Needed for                                                                 |
|:-----------------------------------|:---------------------------------------------------------------------------|
| `spatie/laravel-medialibrary`      | Media routes, `FileAccessController`, `LivewireMediaTrait`, `CustomUrlGenerator` |
| `carlos-meneses/laravel-mpdf`      | `ToPdfTrait` (PDF generation)                                              |
| `maatwebsite/excel`                | `ExcelHelper`                                                              |
| `propaganistas/laravel-phone`      | `LivewirePhonesTrait`                                                      |
| `laravel/sanctum`                  | `Models\User` (`HasApiTokens`)                                             |
| `rappasoft/laravel-authentication-log` | `Models\User` (`AuthenticationLoggable`)                               |
| `wildside/userstamps`              | `Models\User` (`Userstamps`)                                               |
| `mattiverse/laravel-userstamps`    | `MultiguardUserstamps`                                                     |
| `fakerphp/faker`                   | `cache:test` command                                                       |

## Features

### General

- [Models](examples/Models/README.md)
- [Blade utils](examples/Blade/README.md)
- Ready to use [Casts](src/Casts) (`DateCast`, `TrimCast`, `YesBooleanCast`)
- [API generator for WireUi select](examples/API/README.md)
- [Permission management](examples/Permission/README.md)
- [CRUD system](examples/Crud/README.md)
- [Datatable extension](examples/Datatable/README.md)
- [Monthly log rotation](examples/LogRotate/README.md)
- [PDF from Blade](examples/ToPDF/README.md)

### Livewire

- [File upload validation](examples/LivewireFileValidationTrait/README.md)
- [Settings component](examples/Setting/README.md)
- [Livewire components](examples/LivewireComponents/README.md)
- [Media serve routing](examples/Media/README.md) — ⚠️ by default any logged-in user can access any media: define `verifyPermission()` (see the guide)
- [Media upload](examples/LivewireUpload/README.md)

## Artisan Commands

| Command                    | Alias | Description                                              |
|:---------------------------|:------|:---------------------------------------------------------|
| `permission:refresh`       | `pr`  | Sync roles/permissions from `config/sprintflow.php`      |
| `app:command-picker`       | `cp`  | List the project commands and run the selected one       |
| `cache:test {driver}`      |       | Benchmark a cache driver (microseconds, lower is better) |

## Changelog

See [CHANGELOG](CHANGELOG.md) for what has changed recently.

## Contributing

See [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover a security issue, please email michele@lucchina.com instead of using the issue tracker.

## Credits

- [Michele Lucchina](https://github.com/ades4827)

## License

The MIT License (MIT). See [License File](LICENSE) for more information.
