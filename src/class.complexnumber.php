<?php

/*
  ComplexNumber
  A simple and comprehensive complex number class for PHP
  (c) Donald Leung
  MIT License
*/

class ComplexNumber {
  protected $x, $y; // z = x + iy - "x" is the real component, "y" is the imaginary component
  // Class Constants - used internally
  const RECTANGULAR_FORM = 0;
  const MODULUS_ARGUMENT_FORM = 1;
  public function __construct($x, $y = 0, $form = ComplexNumber::RECTANGULAR_FORM) {
    // Type check "x" and "y" - both must be numbers
    if (!(is_numeric($x) && !is_string($x))) throw new InvalidArgumentException("The real part of the complex number \"x\" must be an integer or a float!");
    if (!(is_numeric($y) && !is_string($y))) throw new InvalidArgumentException("The imaginary part of the complex number \"y\" must be an integer or a float!");
    // Check form of constructor arguments
    if ($form === ComplexNumber::RECTANGULAR_FORM) {
      // Assign "x" and "y" directly to real and imaginary components of "z"
      $this->x = $x;
      $this->y = $y;
    } elseif ($form === ComplexNumber::MODULUS_ARGUMENT_FORM) {
      // Treat "x" and "y" as "r" and "theta" instead
      $r = $x;
      $theta = $y;
      // Further input validation
      // Confirm that the modulus "r" is nonnegative
      if ($r < 0) throw new InvalidArgumentException("The modulus \"r\" of the complex number given must be nonnegative!");
      // Confirm that the argument "theta" lies in (-PI, PI]
      if (!($theta > -M_PI && $theta <= M_PI)) throw new InvalidArgumentException("The argument \"theta\" of the complex number given must lie within the range (-PI, PI]!");
      // Use "r" and "theta" to find out "x" and "y" and assigning them to protected properties of ComplexNumber instance
      $this->x = $r * cos($theta);
      $this->y = $r * sin($theta);
    } else {
      throw new InvalidArgumentException("The only input forms supported by the ComplexNumber class are: ComplexNumber::RECTANGULAR_FORM, ComplexNumber::MODULUS_ARGUMENT_FORM");
    }
  }
  public function getReal() {
    // Returns the real component "x" of the complex number "z"
    return $this->x;
  }
  public function getImaginary() {
    // Returns the imaginary component "y" of the complex number "z"
    return $this->y;
  }
  public static function Re($z) {
    // Accepts a complex number "z" as its only argument and returns its real component "x"
    // Alias of ComplexNumber::getReal()
    // Type check "z" - confirm that it is a complex number
    if (!is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::Re() can only accept an instance of ComplexNumber as its only argument!");
    // Return the real component "x" of the complex number "z" via a call to ComplexNumber::getReal()
    return $z->getReal();
  }
  public static function Im($z) {
    // Accepts a complex number "z" as its only argument and returns its imaginary component "y"
    // Alias of ComplexNumber::getImaginary()
    // Type check "z" - confirm that it is a complex number
    if (!is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::Im() can only accept an instance of ComplexNumber as its only argument!");
    // Return the imaginary component "y" of the complex number "z" via a call to ComplexNumber::getImaginary()
    return $z->getImaginary();
  }
  public function getModulus() {
    // Returns the modulus (i.e. magnitude) of the complex number "z"
    return hypot($this->x, $this->y);
  }
  public function getArgument() {
    // Returns arg(z) - range (-PI, PI]
    return atan2($this->y, $this->x);
  }
  public static function arg($z) {
    // Alias of ComplexNumber::getArgument() - better mathematical notation
    // Type check "z" to confirm it is a complex number
    if (!is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::arg() can only receive an instance of ComplexNumber as its only argument!");
    // Return the argument of z by a call to ComplexNumber::getArgument()
    return $z->getArgument();
  }
  public function getComplexConjugate() {
    // Returns the complex conjugate "z* = x - iy" of a complex number "z"
    return new ComplexNumber($this->x, -$this->y);
  }
  public function add($z) {
    // Type Checking - confirm that "z" is either of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("The argument \"z\" to ComplexNumber::add() must be either of: an integer, a float, a complex number");
    // Add "z" to the current complex number and return a new instance of ComplexNumber
    if (is_a($z, "ComplexNumber")) return new ComplexNumber($this->x + $z->x, $this->y + $z->y);
    return new ComplexNumber($this->x + $z, $this->y);
  }
  public function plus($z) {
    // Alias of ComplexNumber::add()
    return $this->add($z);
  }
}

?>
