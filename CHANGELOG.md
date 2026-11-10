# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

Only significant changes are listed; patch releases with minor fixes only are omitted
(see the git history for the full list).

## [5.6.19] - 2026-11-10

### Fixed

- Small layout fix for action btn

## [5.6.18] - 2026-11-09

### Changed

- **BREAKING:** package controllers now extend `Ades4827\Sprintflow\Controllers\Controller` (based on `Illuminate\Routing\Controller`) instead of the application's `App\Http\Controllers\Controller`; methods added to the app base controller are no longer inherited.
- License clarified as MIT (`composer.json` previously declared GPL-2.0-or-later).
- `NumberHelper::isMultiple()` parameters renamed to `$number` / `$divisor`.
- Optional dependencies are now listed in the composer `suggest` section.
- Code comments and developer-facing messages translated to English.

### Deprecated

- `NumberHelper::isMultiple()` named arguments `numero:` / `divisore:`; use `number:` / `divisor:` instead.

### Removed

- `illuminate/support` from `require` (already provided by `laravel/framework`).
- Duplicate `LICENSE.md` file.

### Fixed

- `FileAccessController::download()` now honours the `conversion` parameter (previously it always returned the original file).
- `CrudDatatableController`: undefined `$model_slug` broke `crud.verify_gates` policy checks.
- Explicit nullable parameter types (PHP 8.4 deprecations).

### Security

- `ApiController::translated()`: column name validated and quoted, locale passed as a binding in the raw JSON query.
- `FileAccessController::serve()`: `Content-Disposition` header is now properly escaped, with an ASCII fallback for non-ASCII file names.

## [5.6.17] - 2026-10-30

### Added

- `NumberHelper::applyVat()` helper.

## [5.6.16] - 2026-10-28

### Added

- `MultiguardUserstamps`: support for `userstamp_guard`.

## [5.6.15] - 2026-10-23

### Added

- Livewire table component: column classes and skins.

## [5.6.14] - 2026-10-06

### Added

- Money component: rounding mode support.

## [5.6.13] - 2026-10-02

### Added

- Livewire table component: empty state.

## [5.6.11] - 2026-10-01

### Fixed

- Money formatting.

## [5.6.9] - 2026-08-24

### Added

- Media download URLs with custom file name (`media.downloader` route).

## [5.6.7] - 2026-08-06

### Added

- `LivewirePhonesTrait`.

## [5.4.6] - 2026-08-06

### Added

- Dynamic label for `HasRangeScope`.

## [5.4.5] - 2026-08-05

### Added

- Flux select with search query.

## [5.4.4] - 2026-08-04

### Added

- `ValidFluxIcon` validation rule.

## [5.4.3] - 2026-07-28

### Added

- Livewire return helpers in `LivewireUtilsTrait`.

## [5.4.1] - 2026-07-27

### Changed

- Requires `brick/money` `^0.14`.

## [5.4.0] - 2026-07-27

### Changed

- Requires `brick/money` `^0.12`.

## [5.3.8] - 2026-07-23

### Added

- `ExcelHelper`.

## [5.3.7] - 2026-07-14

### Added

- `ToPdfTrait`: CSS injection.

## [5.3.6] - 2026-07-09

### Added

- CRUD controllers: redirect support.

## [5.3.5] - 2026-07-07

### Added

- `ToPdfTrait`: additional configuration options.

## [5.3.3] - 2026-06-30

### Added

- CRUD: policy check for restore.

## [5.3.2] - 2026-06-30

### Added

- CRUD: optional policy checks via `crud.verify_gates`.

## [5.3.1] - 2026-06-30

### Changed

- **BREAKING:** CRUD `delete()` renamed to `destroy()` and `view()` to `show()`; config key `crud.has_view` renamed to `crud.has_show`.

## [5.3.0] - 2026-06-30

### Added

- CRUD: view route and attribute methods for blade styling.

## [5.2.11] - 2026-06-25

### Added

- Flux UI views, components and settings support.

## [5.2.8] - 2026-06-08

### Added

- `NumberHelper::isMultiple()` helper.

## [5.2.7] - 2026-05-26

### Added

- Number formatter: unit of measure prefix.

## [5.2.4] - 2026-05-26

### Added

- Global `NumberHelper` and `ColorHelper`.
- Number formatter: max precision.

## [5.2.3] - 2026-05-13

### Added

- Tabs skin.

## [5.2.2] - 2026-05-12

### Added

- `ToPdfTrait`.

## [5.2.1] - 2026-05-12

### Changed

- Requires `brick/money` `^0.11`.

## [5.2.0] - 2026-05-12

### Added

- Laravel 13 support.

## [5.1.5] - 2026-04-01

### Added

- Number formatter.

## [5.1.4] - 2026-03-30

### Added

- Settings: fields excluded from editing.

## [5.1.1] - 2026-03-18

### Added

- Settings: grid columns.

## [5.1.0] - 2026-03-16

### Added

- `CrudLivewireEntityController`; CRUD mode selected via `crud.method`.

### Changed

- **BREAKING:** `CrudController` / `CrudEntityController` renamed to `CrudDatatableController` / `CrudDatatableEntityController`. `crud.method` defaults to `livewire`: datatable projects must set it to `datatable`.

## [5.0.6] - 2026-03-11

### Added

- Events dispatched on permissions refresh (`RefreshPermissionsUpdating`, `RefreshPermissionsUpdated`).

## [5.0.0] - 2026-01-15

### Changed

- **BREAKING:** requires Livewire 4.

## [4.1.21] - 2025-12-18

### Added

- Tabs and accordion blade components.

## [4.1.20] - 2025-11-28

### Added

- Monthly log rotation.

## [4.1.16] - 2025-10-20

### Added

- API options from enums.

## [4.1.14] - 2025-10-20

### Added

- `MultiguardUserstamps` trait.

## [4.1.12] - 2025-10-16

### Added

- Action button component.

## [4.1.1] - 2025-09-12

### Added

- Media `CustomUrlGenerator`.

## [4.1.0] - 2025-09-12

### Added

- Media serving routes (`media.getter`) with `FileAccessController`.

### Removed

- **BREAKING:** `compatibility` config option.

## [4.0.11] - 2025-09-10

### Added

- Media upload components.

## [4.0.8] - 2025-08-07

### Added

- Eloquent scope traits.

## [4.0.7] - 2025-07-25

### Added

- Default models and Livewire components (admin/user forms, password edit).

## [4.0.0] - 2025-06-11

### Changed

- **BREAKING:** requires PHP `^8.3`, Laravel 12 and `yajra/laravel-datatables-oracle` `^12.0`.

## [3.2.0] - 2025-04-03

### Added

- Livewire settings component.

## [3.1.2] - 2025-02-28

### Added

- API `checkPermissions`.

## [3.1.1] - 2025-02-27

### Added

- `LivewireMediaTrait`.

## [3.1.0] - 2025-02-25

### Added

- Laravel 12 support.

## [3.0.28] - 2025-02-18

### Changed

- Stable release ready for production.

## [1.0.0] - 2023-11-13

### Added

- Initial release.