# Validation

[![Latest Stable Version](https://poser.pugx.org/harmonyio/validation/v/stable)](https://packagist.org/packages/harmonyio/validation)
[![Build Status](https://travis-ci.org/HarmonyIO/Validation.svg?branch=master)](https://travis-ci.org/HarmonyIO/Validation)
[![Build status](https://ci.appveyor.com/api/projects/status/h5bx81bvncatlium/branch/master?svg=true)](https://ci.appveyor.com/project/PeeHaa/validation/branch/master)
[![Coverage Status](https://coveralls.io/repos/github/HarmonyIO/Validation/badge.svg?branch=master)](https://coveralls.io/github/HarmonyIO/Validation?branch=master)
[![License](https://poser.pugx.org/harmonyio/validation/license)](https://packagist.org/packages/harmonyio/validation)

Async validation library

## Requirements

- PHP 7.3
  - ext-ctype
  - ext-dom
  - ext-fileinfo
  - ext-filter
  - ext-gd
  - ext-hash
  - ext-json
  - ext-libxml
  - ext-mbstring
- Redis

In addition for non-blocking context one of the following event libraries should be installed:

- [ev](https://pecl.php.net/package/ev)
- [event](https://pecl.php.net/package/event)
- [php-uv](https://github.com/bwoebi/php-uv)

## Installation

```
composer require harmonyio/validation
```

## Usage

This library is based on the [amphp concurrency framework](https://amphp.org/).

### Basic usage

Validating a value against a single rule:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Examples;

use Amp\Loop;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\NumericType;

require_once '/path/to/vendor/autoload.php';

Loop::run(static function () {
    /** @var Result $result */
    $result = yield (new NumericType())->validate(true);

    var_dump($result);
});
```

This will result in the following `Result` object:

```php
object(HarmonyIO\Validation\Result\Result)#11 (2) {
  ["valid":"HarmonyIO\Validation\Result\Result":private]=>
  bool(false)
  ["errors":"HarmonyIO\Validation\Result\Result":private]=>
  array(1) {
    [0]=>
    object(HarmonyIO\Validation\Result\Error)#10 (2) {
      ["message":"HarmonyIO\Validation\Result\Error":private]=>
      string(19) "Numeric.NumericType"
      ["parameters":"HarmonyIO\Validation\Result\Error":private]=>
      array(0) {
      }
    }
  }
}
```

### Stacking validation rules

Validation rules can be stacked on top of each other when you want to apply multiple rules to the same value:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Examples;

use Amp\Loop;
use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Combinator\All;
use HarmonyIO\Validation\Rule\Numeric\Maximum;
use HarmonyIO\Validation\Rule\Numeric\Minimum;
use HarmonyIO\Validation\Rule\Numeric\NumericType;

require_once '/path/to/vendor/autoload.php';

Loop::run(static function () {
    /** @var Result $result */
    $result = yield (new All(
        new NumericType(),
        new Minimum(1),
        new Maximum(100)
    ))->validate(102);

    var_dump($result);
});
```

This will result in the following `Result` object:

```php
object(HarmonyIO\Validation\Result\Result)#11 (2) {
  ["valid":"HarmonyIO\Validation\Result\Result":private]=>
  bool(false)
  ["errors":"HarmonyIO\Validation\Result\Result":private]=>
  array(1) {
    [0]=>
    object(HarmonyIO\Validation\Result\Error)#26 (2) {
      ["message":"HarmonyIO\Validation\Result\Error":private]=>
      string(15) "Numeric.Maximum"
      ["parameters":"HarmonyIO\Validation\Result\Error":private]=>
      array(1) {
        [0]=>
        object(HarmonyIO\Validation\Result\Parameter)#24 (2) {
          ["key":"HarmonyIO\Validation\Result\Parameter":private]=>
          string(7) "maximum"
          ["value":"HarmonyIO\Validation\Result\Parameter":private]=>
          float(100)
        }
      }
    }
  }
}
```

### Validation rules

There is already an elaborate list of validation rules implemented. For an overview of all supported rules please see the [documentation in the wiki](wikilink).

All validation rules adhere to the same interface:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Rule;

use Amp\Promise;

interface Rule
{
    /**
     * @param mixed $value
     * @return Promise<HarmonyIO\Validation\Result\Result>
     */
    public function validate($value): Promise;
}
```

This will ensure any value can always be passed in to be validated and it will always result in a promise which resolved to a `Result` object.

If you are missing a validation rule feel free to [open an issue](https://github.com/HarmonyIO/Validation/issues/new?labels=new%20rule).

### Combinators

Combinators are a way to combine multiple validation rules into a single result.

This can be used to build more complex validation rules based on existing rules.

### Results and errors

All validation always result in a `HarmonyIO\Validation\Result\Result` object.  
To check whether a value is valid after validating we can simply call the `HarmonyIO\Validation\Result\Result::isValid()` method which returns a simple boolean.

If the validation fails the validation errors can be accessed using one of two methods on the `Result` object:

- `HarmonyIO\Validation\Result\Result::getFirstError()` - Returns either `null` when there were no errors or a `HarmonyIO\Validation\Result\Error` object otherwise
- `HarmonyIO\Validation\Result\Result::getErrors()` - Returns an array with `HarmonyIO\Validation\Result\Error` objects

#### Errors

When validation results in an invalid result at least one `Error` object will be added to the `Result` object.

An error object always has an error message (which can be used for translation for rendering in your project) and zero or more parameters.  
Parameters are used when failed validation rules are based on dynamic values. I.e. when the validation rule for minimum length of text fails it will result in the following `Error` object:

```php
object(HarmonyIO\Validation\Result\Error)#17 (2) {
      ["message":"HarmonyIO\Validation\Result\Error":private]=>
      string(18) "Text.MinimumLength"
      ["parameters":"HarmonyIO\Validation\Result\Error":private]=>
      array(1) {
        [0]=>
        object(HarmonyIO\Validation\Result\Parameter)#15 (2) {
          ["key":"HarmonyIO\Validation\Result\Parameter":private]=>
          string(6) "length"
          ["value":"HarmonyIO\Validation\Result\Parameter":private]=>
          int(4)
        }
      }
    }
```

### Traditional / blocking usage

Although this project is based on async programming it is also possible to use it in a more traditional blocking way using the `wait()` helper function:

```php
<?php declare(strict_types=1);

namespace HarmonyIO\Validation\Examples;

use HarmonyIO\Validation\Result\Result;
use HarmonyIO\Validation\Rule\Numeric\NumericType;
use function Amp\Promise\wait;

require_once '/path/to/vendor/autoload.php';

/** @var Result $result */
$result = wait((new NumericType())->validate(true));

var_dump($result);
```

## Rules

An overview of all implemented rules (including usage and version information) is documented in [the wiki](https://github.com/HarmonyIO/Validation/wiki/Rules).
