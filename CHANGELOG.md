# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com), and this project adheres to [Semantic Versioning](https://semver.org).

## [Unreleased]

## [v2.0.0] - 2020-02-12

### Added
- Add support for importing from TJUPT
- Add ability to import forums, topics, and posts
- Add ability to import logs from chatroom which is called "shoutbox" in NexusPHP
- Add association process after importing

### Changed
- Users now come with their BONs(`seedbonus`)
- Torrents now come with their comments

### Fixed
- Provide default values for NOT-NULL fields to avoid some failures

### Removed
- Remove support for importing from other systems

## [v1.1.0] - 2019-07-16

### Added
- Add flags for ignoring the users/torrents table when importing ([#2](https://github.com/HDInnovations/gazelle-to-unit3d/pull/2))

### Changed
- Restructure the directory and documentation files ([#3](https://github.com/HDInnovations/gazelle-to-unit3d/pull/3))
- Update PHPUnit to use versions `^7.5` or `^8.0` ([#4](https://github.com/HDInnovations/gazelle-to-unit3d/pull/4))
- Remove PHP CodeSniffer from Composer dependencies ([#5](https://github.com/HDInnovations/gazelle-to-unit3d/pull/5))
- Move test classes to a Tests namespace ([#6](https://github.com/HDInnovations/gazelle-to-unit3d/pull/6))

## [v1.0.2] - 2017-12-15

### Fixed
- Correct the use of the `DB` facade in the command

## [v1.0.1] - 2017-12-15

### Added
- Add unit tests
- Add fallback values for mapping
- Update install instructions as UNIT3D is using Laravel 5.4 (no package autoloading)

## v1.0.0 - 2017-12-15

### Added
- Initial release

[Unreleased]: https://github.com/HDInnovations/xbtit-to-unit3d/compare/master...develop
[v2.0.0]: https://github.com/HDInnovations/gazelle-to-unit3d/compare/v1.1.0...v2.0.0
[v1.1.0]: https://github.com/HDInnovations/gazelle-to-unit3d/compare/v1.0.2...v1.1.0
[v1.0.2]: https://github.com/HDInnovations/gazelle-to-unit3d/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/HDInnovations/gazelle-to-unit3d/compare/v1.0.0...v1.0.1
