# ComplexNumber

A simple and comprehensive complex number class in PHP.  MIT Licensed

## Overview

- Version: *Work In Progress - come back to stay tuned :smile:*
- Owner: [DonaldKellett](https://github.com/DonaldKellett)
- License: MIT License

## File Synopsis

Filename | Description
--- | ---
`LICENSE` | The MIT License
`README.md` | Documentation
`src/` | Source code for the `ComplexNumber` class
`src/class.complexnumber.php` | File containing the `ComplexNumber` class
`test/` | Directory containing the test cases for this project using [PHPTester](https://github.com/DonaldKellett/PHPTester) as the TDD framework
`test/test_cases.php` | The entire test suite for this project
`test/PHPTester-3.1.0/*` | The PHPTester testing framework (version 3.1.0)

## PHP Version

The entire project has been tested and confirmed to work properly in all versions of PHP 7.  Furthermore, the `ComplexNumber` class should also work in PHP 5 (and maybe even some versions of PHP 4) but this has yet to be officially confirmed.  The PHPTester testing framework (version 3.1.0) requires PHP 7 or later but that is irrelevant to this project (except for viewing the passing assertions).

## Contributing

This project is currently WIP and so cannot yet accept any contributions.  Stay tuned :smile:

## Class Synopsis (ComplexNumber)

### Class Constants

```php
ComplexNumber::RECTANGULAR_FORM === 0;
ComplexNumber::MODULUS_ARGUMENT_FORM === 1;
```

Each class constant is assigned a numerical value but it is **not recommended** to use them for purposes other than those mentioned in the documentation below to avoid any possible confusion.

### Initializing a Complex Number (Class Constructor)

The class constructor has two main forms.

#### Rectangular Form (z = x + iy)

```php
ComplexNumber::__construct(mixed $x[, mixed $y = 0]);
```

Initializes a complex number of the form `z = x + iy` (rectangular/Cartesian coordinates).  Both arguments provided must be either an integer or a float - any attempt to pass in other data types with result in an `InvalidArgumentException`.  If the second argument `$y` is not provided, it is assumed to be `0`.  E.g.

```php
new ComplexNumber(3, 4); // 3 + 4i
```

#### Modulus-argument form (z = re^(i * theta))

```php
ComplexNumber::__construct(mixed $r, mixed $theta, int $form === ComplexNumber::MODULUS_ARGUMENT_FORM);
```

If a third argument is provided to the constructor and set equal to `ComplexNumber::MODULUS_ARGUMENT_FORM`, then the first 2 arguments will be treated as `$r` and `$theta` respectively, where `$r` is the modulus of `z` and `$theta` its argument (`arg(z)`).  Again, both arguments must be numbers, and additionally, any `$r < 0` and any `$theta` outside the range `(-PI, PI]` will throw an `InvalidArgumentException`.  E.g.

```php
new ComplexNumber(5, M_PI / 6, ComplexNumber::MODULUS_ARGUMENT_FORM); // 5e^(i * PI / 6) = 2.5 * sqrt(3) + 2.5i
```

#### Any other third argument

If a third argument is provided which is neither of `ComplexNumber::RECTANGULAR_FORM` and `ComplexNumber::MODULUS_ARGUMENT_FORM` then an `InvalidArgumentException` will be thrown.

### Fundamental Properties - Instance and Class Methods

#### getImaginary

```php
mixed ComplexNumber::getImaginary()
```

An instance method that receives no arguments and returns the imaginary component of the complex number either as an integer or as a float.  For example:

```php
$z = new ComplexNumber(2, 5); // 2 + 5i
$z->getImaginary(); // => 5
```

#### Im(z)

```php
mixed ComplexNumber::Im(ComplexNumber $z)
```

A **static class method** that is essentially an alias of `ComplexNumber::getImaginary()` but receives the complex number `$z` as its one and only argument.  Returns the imaginary component of `$z` as an integer or float.  If `$z` is anything but an instance of `ComplexNumber`, an `InvalidArgumentException` is thrown (even for integers and floats).  E.g.

```php
$z = new ComplexNumber(-12, -5); // -12 - 5i
ComplexNumber::Im($z); // => -5
```

#### getReal

```php
mixed ComplexNumber::getReal()
```

An instance method that receives no arguments and returns the real component of the complex number either as an integer or as a float.  E.g.

```php
$z = new ComplexNumber(-7, 24); // -7 + 24i
$z->getReal(); // => -7
```

#### Re(z)

```php
mixed ComplexNumber::Re(ComplexNumber $z);
```

A **static class method** that accepts 1 argument `$z`, an instance of `ComplexNumber`, and returns its real component as an integer or float.  If `$z` is not a `ComplexNumber`, an `InvalidArgumentException` is thrown (including for integers and floats).  For example:

```php
$z = new ComplexNumber(1, 3); // 1 + 3i
ComplexNumber::Re($z); // => 1
```

#### getModulus

```php
float ComplexNumber::getModulus()
```

An instance method that receives no arguments and returns the modulus of the complex number as a float.  E.g.

```php
$z = new ComplexNumber(1, sqrt(3));
$z->getModulus(); // => 2.0 (approx.)
```

#### getArgument

```php
float ComplexNumber::getArgument()
```

An instance method that receives no arguments and returns the argument of the complex number `arg(z)` as a float in the range `(-PI, PI]`.  E.g.

```php
$z = new ComplexNumber(sqrt(2), M_PI / 4, ComplexNumber::MODULUS_ARGUMENT_FORM); // sqrt(2) * e^(i * PI / 4)
$z->getArgument(); // => PI / 4 (approx.)
```

#### arg(z)

```php
float ComplexNumber::arg(ComplexNumber $z)
```

A **static class method** that accepts one argument `$z`, an instance of `ComplexNumber`, and returns its argument `arg(z)` which is a float in the range `(-PI, PI]`.  For example:

```php
$z = new ComplexNumber(3, M_PI / 2, ComplexNumber::MODULUS_ARGUMENT_FORM); // 3e^(i * PI / 2)
ComplexNumber::arg($z); // => PI / 2 (approx.)
```

#### getComplexConjugate

```php
ComplexNumber ComplexNumber::getComplexConjugate()
```

An instance method that receives no arguments and returns its complex conjugate `z*` as a new instance.  E.g.

```php
$z = new ComplexNumber(1, 1); // 1 + i
$z->getComplexConjugate(); // 1 - i
```
