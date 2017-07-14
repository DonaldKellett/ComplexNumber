<?php

/*
  ComplexNumber
  A simple yet comprehensive complex number class for PHP
  v1.2.1
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
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::Re() can only accept an instance of ComplexNumber as its only argument!");
    // If "z" is not yet a complex number convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Return the real component "x" of the complex number "z" via a call to ComplexNumber::getReal()
    return $z->getReal();
  }
  public static function Im($z) {
    // Accepts a complex number "z" as its only argument and returns its imaginary component "y"
    // Alias of ComplexNumber::getImaginary()
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::Im() can only accept an instance of ComplexNumber as its only argument!");
    // If "z" is not yet a complex number convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
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
    // Type check "z" to confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("ComplexNumber::arg() can only receive an instance of ComplexNumber as its only argument!");
    // If "z" is not yet a complex number convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Return the argument of z by a call to ComplexNumber::getArgument()
    return $z->getArgument();
  }
  public static function abs($z) {
    // Type check "z" - ensure it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::abs(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not already a complex number, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Return the absolute value (i.e. the modulus) of "z"
    return $z->getModulus();
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
  public function subtract($z) {
    // Type Checking - confirm that "z" is either of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::subtract(), the argument \"z\" passed in can only be one of: an integer, a float, a complex number");
    // Subtract "z" from the current complex number and return a new instance
    if (is_a($z, "ComplexNumber")) return new ComplexNumber($this->x - $z->x, $this->y - $z->y);
    return new ComplexNumber($this->x - $z, $this->y);
  }
  public function minus($z) {
    // Alias of ComplexNumber::subtract()
    return $this->subtract($z);
  }
  public function multiply($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::multiply(), the argument \"z\" passed in must be either one of: an integer, a float, a complex number");
    // If "z" is not a complex number, convert it into an instance of ComplexNumber
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Apply a suitable formula and return the result
    return new ComplexNumber($this->x * $z->x - $this->y * $z->y, $this->x * $z->y + $this->y * $z->x);
  }
  public function times($z) {
    // Alias of ComplexNumber::multiply()
    return $this->multiply($z);
  }
  public function multipliedBy($z) {
    // Alias of ComplexNumber::multiply()
    return $this->multiply($z);
  }
  public function divide($z) {
    // Type checking - confirm that "z" is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::divide(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // Convert "z" into an instance of ComplexNumber if it is currently an integer or float
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // If z = 0 + 0i (= 0) then throw InvalidArgumentException
    if ($z->x == 0 && $z->y == 0) throw new InvalidArgumentException("In ComplexNumber::divide(), the argument \"z\" provided must be nonzero!");
    // Apply a suitable formula and return the result
    return new ComplexNumber(($this->x * $z->x + $this->y * $z->y) / pow($z->getModulus(), 2), ($this->y * $z->x - $this->x * $z->y) / pow($z->getModulus(), 2));
  }
  public function over($z) {
    // Alias of ComplexNumber::divide()
    return $this->divide($z);
  }
  public function dividedBy($z) {
    // Alias of ComplexNumber::divide()
    return $this->divide($z);
  }
  public static function sqrt($z) {
    // Type check "z" - ensure it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::sqrt(), the argument \"z\" provided must be one of: an integer, a float, a complex number");
    // If "z" is currently not a complex number, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Apply a suitable formula and return the result
    return new ComplexNumber(sqrt($z->getModulus()), ComplexNumber::arg($z) / 2, ComplexNumber::MODULUS_ARGUMENT_FORM);
  }
  public static function exp($z) {
    // Type check "z" to confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::exp(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Apply a suitable formula and return the result
    return new ComplexNumber(exp($z->x) * cos($z->y), exp($z->x) * sin($z->y));
  }
  public static function log($z, $base = M_E) {
    // Type check "z" and "base" to confirm that both are one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::log(), the first argument \"z\" passed in must be one of: an integer, a float, a complex number");
    if (!is_int($base) && !is_float($base) && !is_a($base, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::log(), the second argument \"base\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not already an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // If the given base is e = 2.718281828459045 ... then directly evaluate the complex logarithm of z
    if ($base === M_E) {
      // Further input validation - "z" cannot be zero!
      if ($z->x == 0 && $z->y == 0) throw new InvalidArgumentException("In ComplexNumber::log(), the first argument \"z\" passed in must be nonzero!");
      // Apply suitable formula and return the result
      return new ComplexNumber(log($z->getModulus()), ComplexNumber::arg($z));
    }
    // If the given base is not "e" but another real number then convert it into an instance of ComplexNumber
    if (!is_a($base, "ComplexNumber")) $base = new ComplexNumber($base);
    // Ensure base is nonzero
    if ($base->x == 0 && $base->y == 0) throw new InvalidArgumentException("In ComplexNumber::log(), the second argument \"base\" passed in must be nonzero!");
    // Use a suitable logarithmic identity and return the result
    return ComplexNumber::log($z)->dividedBy(ComplexNumber::log($base));
  }
  public static function pow($z, $w) {
    // Type check "z" and "w" - ensure that both are one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::pow(), the first argument \"z\" passed in must be one of: an integer, a float, a complex number");
    if (!is_int($w) && !is_float($w) && !is_a($w, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::pow(), the second argument \"w\" passed in must be one of: an integer, a float, a complex number");
    // Convert any existing real numbers into complex numbers for ease of calculation
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    if (!is_a($w, "ComplexNumber")) $w = new ComplexNumber($w);
    // Special Case: z = 0 + 0i
    if ($z->x == 0 && $z->y == 0) {
      // If the real part of w is strictly greater than 0 then the result is 0 + 0i (in agreement with WolframAlpha)
      if ($w->x > 0) return new ComplexNumber(0);
      // Else, if the real part of w is exactly 0 then 1 + 0i should be returned (in agreement with the core PHP function: pow())
      if ($w->x == 0) return new ComplexNumber(1);
      // Otherwise, the result is complex infinity which should throw an error
      throw new InvalidArgumentException("(0 + 0i)^w evaluates to \"complex infinity\" for all \"w\" where Re(w) < 0");
    }
    // General Case: apply a suitable formula and return the result
    return ComplexNumber::exp($w->times(ComplexNumber::log($z)));
  }
  public static function sinh($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::sinh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not already an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Use the exponential form of hyperbolic sine and return the result
    return ComplexNumber::exp($z)->minus(ComplexNumber::exp((new ComplexNumber(-1))->times($z)))->dividedBy(2);
  }
  public static function cosh($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::cosh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not an instance of ComplexNumber yet, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Use the exponential form of cosh(z) and return the result
    return ComplexNumber::exp($z)->plus(ComplexNumber::exp((new ComplexNumber(-1))->times($z)))->dividedBy(2);
  }
  public static function tanh($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::tanh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not yet an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Special Case: where z = i * pi * n - i * pi / 2, tanh(z) evaluates to complex infinity.  Throw an InvalidArgumentException in this case
    if ($z->x == 0 && fmod($z->y + M_PI / 2, M_PI) == 0.0) throw new InvalidArgumentException("When z = i * pi * n - i * pi / 2 (where n is an integer), tanh(z) evaluates to \"complex infinity\"!");
    // Otherwise, use the hyperbolic identity tanh(z) = sinh(z) / cosh(z) and return the result
    return ComplexNumber::sinh($z)->dividedBy(ComplexNumber::cosh($z));
  }
  public static function asinh($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::asinh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Use the logarithmic form of arsinh(z) and return the result
    return ComplexNumber::log($z->plus(ComplexNumber::sqrt((new ComplexNumber(1))->plus($z->times($z)))));
  }
  public static function arsinh($z) {
    // Alias of ComplexNumber::asinh()
    return ComplexNumber::asinh($z);
  }
  public static function acosh($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::acosh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Use the logarithmic form as provided by WolframAlpha and return the result
    return ComplexNumber::log($z->plus(ComplexNumber::sqrt((new ComplexNumber(-1))->plus($z))->times(ComplexNumber::sqrt((new ComplexNumber(1))->plus($z)))));
  }
  public static function arcosh($z) {
    // Alias of ComplexNumber::acosh()
    return ComplexNumber::acosh($z);
  }
  public static function atanh($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, "ComplexNumber")) throw new InvalidArgumentException("In ComplexNumber::atanh(), the argument \"z\" passed in must be one of: an integer, a float, a complex number");
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, "ComplexNumber")) $z = new ComplexNumber($z);
    // Special Case - where z = 1 + 0i, artanh(z) evaluates to positive infinity.  Throw an InvalidArgumentException in this case
    if ($z->x == 1 && $z->y == 0) throw new InvalidArgumentException("artanh(1) evaluates to \"positive infinity\"!");
    // Special Case - where z = -1 + 0i, artanh(z) evaluates to negative infinity.  THrow an InvalidArgumentException in this case
    if ($z->x == -1 && $z->y == 0) throw new InvalidArgumentException("artanh(-1) evaluates to \"negative infinity\"!");
    // Otherwise, use the logarithmic form as provided by WolframAlpha and return the result
    return (new ComplexNumber(-1))->times(ComplexNumber::log((new ComplexNumber(1))->minus($z)))->plus(ComplexNumber::log((new ComplexNumber(1))->plus($z)))->dividedBy(2);
  }
  public static function artanh($z) {
    // Alias of ComplexNumber::atanh()
    return ComplexNumber::atanh($z);
  }
  public static function sin($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::sin(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Use a suitable identity and return the result
    return (new ComplexNumber(0, -1))->times(ComplexNumber::sinh((new ComplexNumber(0, 1))->times($z)));
  }
  public static function cos($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::cos(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Use a suitable identity and return the result
    return ComplexNumber::cosh((new ComplexNumber(0, 1))->times($z));
  }
  public static function tan($z) {
    // Type check "z" - confirm it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::tan(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Special case: where z = (pi * n - pi / 2) + 0i (where n is an integer), tan(z) is undefined!  Throw an InvalidArgumentException in this case
    if (fmod($z->x + M_PI / 2, M_PI) == 0.0 && $z->y == 0) throw new InvalidArgumentException('When z = pi * n - pi / 2, tan(z) is undefined!');
    // Otherwise, use the trig identity tan(z) = sin(z) / cos(z) and return the result
    return ComplexNumber::sin($z)->dividedBy(ComplexNumber::cos($z));
  }
  public static function asin($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::asin(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Use a suitable identity and return the result
    return (new ComplexNumber(0, -1))->times(ComplexNumber::asinh($z->times(new ComplexNumber(0, 1))));
  }
  public static function arcsin($z) {
    // Alias of ComplexNumber::asin()
    return ComplexNumber::asin($z);
  }
  public static function acos($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::acos(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Special Case - where z = 1 + 0i, arccos(1) evaluates to 0
    if ($z->x == 1 && $z->y == 0) return new ComplexNumber(0);
    // Otherwise use a suitable identity and return the result
    return ComplexNumber::acosh($z)->times(ComplexNumber::sqrt((new ComplexNumber(1))->minus($z)))->dividedBy(ComplexNumber::sqrt($z->minus(1)));
  }
  public static function arccos($z) {
    // Alias of ComplexNumber::acos()
    return ComplexNumber::acos($z);
  }
  public static function atan($z) {
    // Type check "z" - confirm that it is one of: an integer, a float, a complex number
    if (!is_int($z) && !is_float($z) && !is_a($z, 'ComplexNumber')) throw new InvalidArgumentException('In ComplexNumber::atan(), the argument "z" passed in must be one of: an integer, a float, a complex number');
    // If "z" is not an instance of ComplexNumber, convert it into one
    if (!is_a($z, 'ComplexNumber')) $z = new ComplexNumber($z);
    // Special Case: arctan(i) = i * Infinity (throw InvalidArgumentException)
    if ($z->x == 0 && $z->y == 1) throw new InvalidArgumentException('arctan(i) = i * Infinity');
    // Special Case: arctan(-i) = -i * Infinity (throw InvalidArgumentException)
    if ($z->x == 0 && $z->y == -1) throw new InvalidArgumentException('arctan(-i) = -i * Infinity');
    // Otherwise, use a suitable identity and return the result
    return (new ComplexNumber(0, -1))->times(ComplexNumber::atanh($z->times(new ComplexNumber(0, 1))));
  }
  public static function arctan($z) {
    // Alias of ComplexNumber::atan()
    return ComplexNumber::atan($z);
  }
}

?>
