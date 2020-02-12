# tjupt-to-unit3d

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

An artisan package to import a TJUPT(NexusPHP) database into [UNIT3D].

## Install

Via Composer

```bash
$ composer require robinwongm/tjupt-to-unit3d --dev
```

To install, just:
- Require this package from your [UNIT3D][unit3d] install.
- Add an empty `imports` entry to your database config.

## Usage

**It's recommended to use the package under a fresh installation of [UNIT3D][unit3d].**

```bash
 # Optional but recommended: Make sure a fresh installation
php artisan migrate:fresh --seed

# IMPORTANT: Run migrations from this package
php artisan migrate --path=vendor/robinwongm/tjupt-to-unit3d/src/migrations/2020_02_10_054032_add_nexus_compatibility.php

# Import
php artisan unit3d:from-tjupt --host=<YOUR TJUPT DATABASE HOST> --database=<YOUR TJUPT DATABASE NAME> --username=<YOUR TJUPT DATABASE USERNAME> --password=<YOUR TJUPT DATABASE PASSWORD>

# Optional: Remove temporarily-added columns
php artisan migrate --path=vendor/robinwongm/tjupt-to-unit3d/src/migrations/2020_02_10_054032_add_nexus_compatibility.php
```

For other instructions on usage, run:

```bash
php artisan unit3d:from-tjupt --help
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) and [CODE_OF_CONDUCT](.github/CODE_OF_CONDUCT.md) for details.

## Credits

- [pxgamer][link-author] - original author
- robinWongM - fork maintainer
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[unit3d]: https://github.com/unit3d/unit3d

[ico-version]: https://img.shields.io/packagist/v/robinwongm/tjupt-to-unit3d.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/robinWongM/tjupt-to-unit3d/master.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/codecov/c/github/robinWongM/tjupt-to-unit3d.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/robinwongm/tjupt-to-unit3d.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/robinwongm/tjupt-to-unit3d
[link-travis]: https://travis-ci.org/robinWongM/tjupt-to-unit3d
[link-code-quality]: https://codecov.io/gh/robinWongM/tjupt-to-unit3d
[link-downloads]: https://packagist.org/packages/robinwongm/tjupt-to-unit3d
[link-author]: https://github.com/pxgamer
[link-contributors]: ../../contributors
