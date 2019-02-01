
[![Build Status](https://travis-ci.org/aretche/php-code-quality.svg?branch=master)](https://travis-ci.org/aretche/php-code-quality)
[![Code Style](https://styleci.io/repos/168615452/shield)](https://styleci.io/repos/168615452)

# Code Quality for PHP packages

This package provides code quality scripts that can be run via
[Composer](https://github.com/composer/composer).

The scripts also work on continous integration (CI) servers like Jenkins. 

## Used packages

### [FriendsOfPHP/PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer)

Currently used for fixing the code.   
Fixes all files in `src` directory according Symfony coding guidelines.

This package is not used for checking (linting), because PHP_Codesniffer prints a 
more readable output.

### [squizlabs/PHP_CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer)

Currently used for checking (linting) the code.   
Sniffs all files in `src` directory.

This package could also be used for fixing, but PHP-CS-Fixer does it better.

### [phpmd/phpmd](https://github.com/phpmd/phpmd)

Used for mess detection.   
Runs the defined ruleset (`config/phpmd.xml`) on all files in `src` directory.

### [phpstan/phpstan](https://github.com/phpstan/phpstan)

Used for static analysis.   
Perform static analysis on all files in `src` directory.

### [phpunit/phpunit](https://phpunit.de)

Used for testing the code.   
Must be configured with a `phpunit.xml` file in your root folder.

### [lchrusciel/api-test-case](https://github.com/lchrusciel/ApiTestCase)

Based on PHPUnit and used for testing API methods.
   

### [phpspec/phpspec](https://github.com/phpspec/phpspec)

Used for testing (SpecBDD) the code.   
Must be configured with a `phpspec.yml` file in your root folder.

We are using the `leanphp/phpspec-code-coverage` extension for generating coverage reports.   
This extension requires a `phpspec-coverage.yml` file in your root folder and Xdebug enabled.

## Installation

Run `composer require --dev aretche/php-code-quality` to install this package.

After installing, insert the desired scripts to your `composer.json`.

```json
{
    "scripts": {
        "lint": "Karriere\\CodeQuality\\CodeStyleChecker::run",
        "fix": "Karriere\\CodeQuality\\CodeStyleFixer::run",
        "md": "Karriere\\CodeQuality\\MessDetector::run",
        "static": "Karriere\\CodeQuality\\StaticAnalyzer::run",
        "unit-test": "Karriere\\CodeQuality\\UnitTest::run",
        "unit-coverage": "Karriere\\CodeQuality\\UnitCoverage::run",
        "check-coverage": "Karriere\\CodeQuality\\CloverCoverageCheck::run",
        "spec-test": "Karriere\\CodeQuality\\SpecificationTest::run",
        "spec-coverage": "Karriere\\CodeQuality\\CodeCoverage::run"
    }
}
```

## Usage

You can run a script like this: `composer {script} -- {options}`.

> If you are using Git-Shell on Windows (or Git-Shell in Intellij 
> Terminal on Windows), call scripts like this: `composer.bat {script}`.
> Otherwise colors will not work.

You can disable `TTY` by adding the `--notty` flag (needed for Jenkins).   
On Windows platform it's disabled automatically.

```
composer {script} -- --env=jenkins --notty
```

### Scripts

#### `lint`

```
Usage:
  lint [--] [options]

Options:
      --env    Specifiy the environment. Possible values:
               'local': prints output on command-line.
               'jenkins': generates a checkstyle report file.
      --fail   Exit with 1 if linting fails.
      --notty  Disable TTY.
```

#### `static`

```
Usage:
  static [--] [options]

Options:
      --env    Specifiy the environment. Possible values:
               'local': prints output on command-line.
               'jenkins': generates a json report file (phpstan.json).
      --notty  Disable TTY.
```


#### `md`

```
Usage:
  md [--] [options]

Options:
      --env    Specifiy the environment. Possible values:
               'local': prints output on command-line.
               'jenkins': generates a xml report file (phpmd.xml).
      --notty  Disable TTY.
```

#### `fix`

```
Usage:
  fix [--] [options]

Options:
      --dry-run  Show changes to be applyed. 
      --notty    Disable TTY.
```

#### `unit-test`

```
Usage:
  unit-test [--] [options]

Options:
      --fail     Exit with 1 if tests fail.
      --notty    Disable TTY.
   -v --verbose  Increase the verbosity of messages.
```

#### `unit-coverage`

```
Usage:
  unit-coverage [--] [options]

Options:
      --env    Specifiy the environment. Possible values:
               'local': generate html report in coverage directory.
               'jenkins': generates clover.xml coverage report in root directory.
      --notty  Disable TTY.

```

#### `check-coverage`

```
Usage:
  check-coverage [--] [options]

Options:
      --min-coverage    Specify minimal coverage level. Possible
                        values: 0, 25, 50, 100 (default)    
      --fail            Exit with 1 on insufficient coverage.
      --notty           Disable TTY.
```

#### `spec-test`

```
Usage:
  spec-test [--] [options]

Options:
      --fail     Exit with 1 if tests fail.
      --notty    Disable TTY.
   -v --verbose  Increase the verbosity of messages.
```

#### `spec-coverage`

```
Usage:
  spec-coverage [--] [options]

Options:
      --env    Specifiy the environment. Possible values:
               'local': prints output on command-line.
               'jenkins': generates a JUnit report file.
      --notty  Disable TTY.

```


## Using custom matchers

This package integrates [karriere/phpspec-matchers](https://github.com/karriereat/phpspec-matchers).
In order to use the custom matchers defined in this package,
please include the extension configuration in your `phpspec.yml`.

## FAQ

### Why do I have to provide two phpspec configuration files?

The code-coverage-extension slows down the phpspec tests, so we excluded it from the
normal configuration file. Keep tests fast!

### How do I increase the verbosity of the `test`-script output?

Run `composer test -- -v`.
