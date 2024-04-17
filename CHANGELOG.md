# ramsey/composer-repl-lib Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](http://keepachangelog.com/en/1.0.0/)
and this project adheres to [Semantic Versioning](http://semver.org/spec/v2.0.0.html).

## 1.3.0 - 2024-04-17

### Added

- Expand phpunit/phpunit version to include `^11.0`.
- Expand psy/psysh version to include `^0.12.0`.
- Expand symfony/console version to include `^7.0`.
- Expand symfony/finder version to include `^7.0`.
- Expand symfony/process version to include `^7.0`.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.2.0 - 2023-03-18

### Added

- Nothing.

### Changed

- Set minimum required version of PHP to 8.1.
- Set minimum required version of PHPUnit to 10.0.
- Set minimum required versions of symfony/console, symfony/finder, and symfony/process to 6.0.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.1.0 - 2022-04-16

### Added

- Add `Repl::getConfig()` and `Repl::getScopeVariables()` public methods.
- Set a `COMPOSER_REPL` environment variable with the value `"1"` when running in the context of the REPL.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.

## 1.0.1 - 2022-01-18

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Move composer/composer to `require` dependencies, since the REPL no longer runs within the scope of `composer.phar`.

## 1.0.0 - 2022-01-18

### Added

- Nothing.

### Changed

- Move all library code from [ramsey/composer-repl](https://github.com/ramsey/composer-repl) to [ramsey/composer-repl-lib](https://github.com/ramsey/composer-repl-lib).

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- Nothing.
