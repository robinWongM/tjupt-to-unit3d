# gazelle-to-unit3d

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Style CI][ico-styleci]][link-styleci]
[![Code Coverage][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

An artisan package to import a [Gazelle] database into [UNIT3D].

## Install

Via Composer

```bash
$ composer require pxgamer/gazelle-to-unit3d --dev
```

To install, just:
- Require this package from your [UNIT3D][unit3d] install.
- Using Laravel <5.5, add the `pxgamer\GazelleToUnit3d\ServiceProvider::class` to your [providers].
- Add an empty `imports` entry to your database config.

## Usage

For instructions on usage, run:

```bash
php artisan unit3d:from-gazelle --help
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

- [pxgamer][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[unit3d]: https://github.com/unit3d/unit3d
[gazelle]: https://github.com/whatcd/gazelle

[ico-version]: https://img.shields.io/packagist/v/pxgamer/gazelle-to-unit3d.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/HDInnovations/gazelle-to-unit3d/master.svg?style=flat-square
[ico-styleci]: https://styleci.io/repos/114096504/shield
[ico-code-quality]: https://img.shields.io/codecov/c/github/HDInnovations/gazelle-to-unit3d.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/pxgamer/gazelle-to-unit3d.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/pxgamer/gazelle-to-unit3d
[link-travis]: https://travis-ci.org/HDInnovations/gazelle-to-unit3d
[link-styleci]: https://styleci.io/repos/114096504
[link-code-quality]: https://codecov.io/gh/HDInnovations/gazelle-to-unit3d
[link-downloads]: https://packagist.org/packages/pxgamer/gazelle-to-unit3d
[link-author]: https://github.com/pxgamer
[link-contributors]: ../../contributors
