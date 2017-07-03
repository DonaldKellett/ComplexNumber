<?php

/*
  Test Cases for the ComplexNumber class using Test-Driven Development (TDD)
  (c) Donald Leung
  MIT License
*/

// Use ComplexNumber class for testing
require '../src/class.complexnumber.php';
// Use PHPTester v3.1.0
require 'PHPTester-3.1.0/class.phptester.php';
// Create new instance for testing
$test = new PHPTester;

// Executing the test cases - open in a browser to view results
$test->describe("The ComplexNumber class", function () use ($test) {
  $test->it("should have protected properties \"x\" and \"y\"", function () use ($test) {
    $complex_number = new ReflectionClass('ComplexNumber');
    $protected = $complex_number->getProperties(ReflectionProperty::IS_PROTECTED);
    $protected = array_map(function ($property) {return $property->getName();}, $protected);
    $test->assert_similar($protected, array("x", "y"));
  });
  $test->it("should have a working class constructor which receives its arguments in rectangular form and constructs the new instance of the complex number correctly", function () use ($test) {
    $one = new ComplexNumber(1);
    $i = new ComplexNumber(0, 1); // 0 + 1i = i
    $minus_one = new ComplexNumber(-1);
    $minus_i = new ComplexNumber(0, -1); // 0 + -1i = -i
    $test->assert_equals(ComplexNumber::Im($one), 0, "The real number 1 has an imaginary component of 0");
    $test->assert_equals(ComplexNumber::Re($one), 1, "The real number 1 has a real component of 1 by definition");
    $test->assert_equals(ComplexNumber::Im($i), 1, "The imaginary unit i has an imaginary component of 1 by definition");
    $test->assert_equals(ComplexNumber::Re($i), 0, "An imaginary number has a real component of 0 by definition");
    $test->assert_equals(ComplexNumber::Im($minus_one), 0, "The real number -1 should have an imaginary component of 0");
    $test->assert_equals(ComplexNumber::Re($minus_one), -1, "The real number -1 has a real component of -1 by definition");
    $test->assert_equals(ComplexNumber::Im($minus_i), -1, "The imaginary component of -i should be -1");
    $test->assert_equals(ComplexNumber::Re($minus_i), 0, "An imaginary number has a real component of 0 by definition");
    $zero = new ComplexNumber(0);
    $test->assert_equals(ComplexNumber::Im($zero), 0, "Zero should have an imaginary component of 0");
    $test->assert_equals(ComplexNumber::Re($zero), 0, "Zero should have a real component of 0");
    $z1 = new ComplexNumber(3, 4); // 3 + 4i - both components positive
    $test->assert_equals(ComplexNumber::Im($z1), 4, "Im(3 + 4i) = 4");
    $test->assert_equals(ComplexNumber::Re($z1), 3, "Re(3 + 4i) = 3");
    $z2 = new ComplexNumber(-5 / 3, 7 / 3); // -5 / 3 + (7 / 3)i - real component negative, imaginary component positive
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 7 / 3, 1e-9, "Im(-5 / 3 + (7 / 3)i) = 7 / 3");
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), -5 / 3, 1e-9, "Re(-5 / 3 + (7 / 3)i) = -5 / 3");
    $z3 = new ComplexNumber(12, -5); // 12 - 5i - real component positive, imaginary component negative
    $test->assert_equals(ComplexNumber::Im($z3), -5, "Im(12 - 5i) = -5");
    $test->assert_equals(ComplexNumber::Re($z3), 12, "Re(12 - 5i) = 12");
    $z4 = new ComplexNumber(-24.3, -20.1); // -24.3 - 20.1i - both components negative
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), -20.1, 1e-9, "Im(-24.3 - 20.1i) = -20.1");
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), -24.3, 1e-9, "Re(-24.3 - 20.1i) = -24.3");
  });
  $test->it("should have a class constructor that checks the arguments passed in and throws an InvalidArgumentException if necessary", function () use ($test) {
    $test->expect_error("Real component is not a number", function () {
      new ComplexNumber("Hello World", 1000);
    });
    $test->expect_error("Real component is not a number (2)", function () {
      new ComplexNumber(true, 1000);
    });
    $test->expect_error("Real component is not a number (3)", function () {
      new ComplexNumber(array(4), 3);
    });
    $test->expect_error("Imaginary component is not a number", function () {
      new ComplexNumber(-666, "Goodbye World");
    });
    $test->expect_error("Imaginary component is not a number (2)", function () {
      new ComplexNumber(-666, false);
    });
    $test->expect_error("Imaginary component is not a number (3)", function () {
      new ComplexNumber(24, array(-7));
    });
    $test->expect_error("Both components are invalid", function () {
      new ComplexNumber(false, true);
    });
  });
  $test->it("should allow defining a complex number via modulus-argument form", function () use ($test) {
    $one = new ComplexNumber(1, 0, ComplexNumber::MODULUS_ARGUMENT_FORM); // e^(0i) = 1
    $i = new ComplexNumber(1, M_PI / 2, ComplexNumber::MODULUS_ARGUMENT_FORM); // e^(i * PI / 2) = i
    $minus_one = new ComplexNumber(1, M_PI, ComplexNumber::MODULUS_ARGUMENT_FORM); // e^(i * PI) = -1 (from Euler's Identity)
    $minus_i = new ComplexNumber(1, -M_PI / 2, ComplexNumber::MODULUS_ARGUMENT_FORM); // e^(i * -PI / 2) = -i
    $test->assert_fuzzy_equals(ComplexNumber::Im($one), 0, 1e-9, "The real number 1 has an imaginary component of 0");
    $test->assert_fuzzy_equals(ComplexNumber::Re($one), 1, 1e-9, "The real number 1 has a real component of 1 by definition");
    $test->assert_fuzzy_equals(ComplexNumber::Im($i), 1, 1e-9, "The imaginary unit i has an imaginary component of 1 by definition");
    $test->assert_fuzzy_equals(ComplexNumber::Re($i), 0, 1e-9, "An imaginary number has a real component of 0 by definition");
    $test->assert_fuzzy_equals(ComplexNumber::Im($minus_one), 0, 1e-9, "The real number -1 should have an imaginary component of 0");
    $test->assert_fuzzy_equals(ComplexNumber::Re($minus_one), -1, 1e-9, "The real number -1 has a real component of -1 by definition");
    $test->assert_fuzzy_equals(ComplexNumber::Im($minus_i), -1, 1e-9, "The imaginary component of -i should be -1");
    $test->assert_fuzzy_equals(ComplexNumber::Re($minus_i), 0, 1e-9, "An imaginary number has a real component of 0 by definition");
    $zero = new ComplexNumber(0, lcg_value(), ComplexNumber::MODULUS_ARGUMENT_FORM); // 0 * e^(i * theta) = 0 for any theta
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero), 0, 1e-9, "Zero should have an imaginary component of 0");
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero), 0, 1e-9, "Zero should have a real component of 0");
    $z1 = new ComplexNumber(2, -5 * M_PI / 6, ComplexNumber::MODULUS_ARGUMENT_FORM); // 2e^(-5 * i * PI / 6) = 2(cos(-5 * PI / 6) + isin(-5 * PI / 6)) = -sqrt(3) - i
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), -1);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), -sqrt(3));
    $z2 = new ComplexNumber(2, -M_PI / 3, ComplexNumber::MODULUS_ARGUMENT_FORM); // 2e^(-i * PI / 3) = 2(cos(-PI / 3) + isin(-PI / 3)) = 1 - sqrt(3)i
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), -sqrt(3));
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 1);
    $z3 = new ComplexNumber(sqrt(2), M_PI / 4, ComplexNumber::MODULUS_ARGUMENT_FORM); // sqrt(2) * e^(i * PI / 4) = 1 + i
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 1);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), 1);
  });
  $test->it("should impose additional validation rules on \"r\" and \"theta\" when ComplexNumber::MODULUS_ARGUMENT_FORM setting is used", function () use ($test) {
    $test->expect_error("r = -0.1 is invalid as r >= 0", function () {
      new ComplexNumber(-0.1, 1, ComplexNumber::MODULUS_ARGUMENT_FORM);
    });
    $test->expect_error("theta = -PI is invalid since the accepted range is (-PI, PI]", function () {
      new ComplexNumber(15, -M_PI, ComplexNumber::MODULUS_ARGUMENT_FORM);
    });
    $test->expect_error("theta = 4 is invalid since 4 > PI", function () {
      new ComplexNumber(4.5, 4, ComplexNumber::MODULUS_ARGUMENT_FORM);
    });
  });
  $test->it("should not accept any other form other than ComplexNumber::RECTANGULAR_FORM and ComplexNumber::MODULUS_ARGUMENT_FORM", function () use ($test) {
    $test->expect_error("Form \"-1\" should not be accepted", function () {
      new ComplexNumber(1, 1, -1);
    });
    $test->expect_error("Form \"0.5\" should not be accepted", function () {
      new ComplexNumber(1, 1, 0.5);
    });
    $test->expect_error("Form \"2\" should not be accepted", function () {
      new ComplexNumber(1, 1, 2);
    });
    $test->expect_error("Form \"false\" should not be accepted", function () {
      new ComplexNumber(1, 1, false);
    });
    $test->expect_error("Form \"Hello World\" should not be accepted", function () {
      new ComplexNumber(1, 1, "Hello World");
    });
  });
  $test->it("should have a working ComplexNumber::getReal() and ComplexNumber::getImaginary() method", function () use ($test) {
    $z = new ComplexNumber(sqrt(3), 1); // sqrt(3) + i
    $test->assert_fuzzy_equals($z->getReal(), sqrt(3));
    $test->assert_fuzzy_equals($z->getImaginary(), 1);
    // No further tests are required since ComplexNumber::Im() and ComplexNumber::Re() actually invoke these two methods
  });
  $test->it("should have a working ComplexNumber::getModulus() method", function () use ($test) {
    $z1 = new ComplexNumber(3, 4);
    $z2 = new ComplexNumber(-1, 1);
    $z3 = new ComplexNumber(4, -2 * M_PI / 3, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $zero = new ComplexNumber(0);
    $test->assert_fuzzy_equals($z1->getModulus(), 5);
    $test->assert_fuzzy_equals($z2->getModulus(), sqrt(2));
    $test->assert_fuzzy_equals($z3->getModulus(), 4);
    $test->assert_fuzzy_equals($zero->getModulus(), 0);
  });
  $test->it("should have a working ComplexNumber::getArgument() method for any nonzero complex number", function () use ($test) {
    $z1 = new ComplexNumber(3, 4);
    $z2 = new ComplexNumber(-1, 1);
    $z3 = new ComplexNumber(4, -2 * M_PI / 3, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $test->assert_fuzzy_equals($z1->getArgument(), 0.927295218, 1e-9, "arctan(4 / 3) = 0.927295218");
    $test->assert_fuzzy_equals($z2->getArgument(), 3 * M_PI / 4);
    $test->assert_fuzzy_equals($z3->getArgument(), -2 * M_PI / 3);
    $one = new ComplexNumber(1);
    $i = new ComplexNumber(0, 1);
    $minus_one = new ComplexNumber(-1);
    $minus_i = new ComplexNumber(0, -1);
    $test->assert_fuzzy_equals($one->getArgument(), 0);
    $test->assert_fuzzy_equals($i->getArgument(), M_PI / 2);
    $test->assert_fuzzy_equals($minus_one->getArgument(), M_PI);
    $test->assert_fuzzy_equals($minus_i->getArgument(), -M_PI / 2);
  });
  $test->it("should give the value of arg(0 + 0i) to be 0 in agreement with Wolfram Alpha", function () use ($test) {
    $zero = new ComplexNumber(0);
    $test->assert_fuzzy_equals($zero->getArgument(), 0);
  });
  $test->it("should support the alias static class method ComplexNumber::arg() to conform to mathematical notation", function () use ($test) {
    $z = new ComplexNumber(4, -2 * M_PI / 3, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z), -2 * M_PI / 3);
  });
  $test->it("Im(z), Re(z) and arg(z) should throw with invalid input", function () use ($test) {
    $test->expect_error("Ordinary number arguments should be rejected", function () {
      ComplexNumber::Im(5);
    });
    $test->expect_error("Boolean arguments should be rejected", function () {
      ComplexNumber::Re(true);
    });
    $test->expect_error("Strings should be rejected", function () {
      ComplexNumber::arg("2 + 3i");
    });
  });
  $test->it("should have a working ComplexNumber::getComplexConjugate() method", function () use ($test) {
    $z = new ComplexNumber(73, 55); // 73 + 55i
    $z_star = $z->getComplexConjugate(); // 73 - 55i
    $z_star_star = $z_star->getComplexConjugate();
    // Immutability test on z
    $test->assert_equals(ComplexNumber::Im($z), 55);
    $test->assert_equals(ComplexNumber::Re($z), 73);
    // Testing "x" and "y" on z*
    $test->assert_equals(ComplexNumber::Im($z_star), -55);
    $test->assert_equals(ComplexNumber::Re($z_star), 73);
    // Testing "x" and "y" on z** - z** should equal z
    $test->assert_equals(ComplexNumber::Im($z_star_star), 55);
    $test->assert_equals(ComplexNumber::Re($z_star_star), 73);
  });
  $test->it("should have a working ComplexNumber::add() method that can add both ordinary numbers and complex numbers to the given complex number", function () use ($test) {
    $z = new ComplexNumber(7.5, -2.3);
    $z1 = $z->add(34.11);
    $z2 = $z->add(-5.579);
    $z3 = $z->add(new ComplexNumber(-8.8, 2.1));
    $z4 = $z->add(new ComplexNumber(-5, -4));
    // Immutability test on z
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 7.5);
    // Test different sums of complex numbers
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), 41.61);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 1.921);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), -0.2);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -1.3);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), -6.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), 2.5);
  });
  $test->it("ComplexNumber::add() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->add("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->add("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->add(true);
    });
    $test->expect_error("An array should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->add(array(3, 5));
    });
  });
  $test->it("should have an instance method ComplexNumber::plus() which is an alias of ComplexNumber::add()", function () use ($test) {
    $z = new ComplexNumber(7.5, -2.3);
    $w = $z->plus(new ComplexNumber(-8.5, 4.3));
    $test->assert_fuzzy_equals(ComplexNumber::Im($w), 2);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w), -1);
  });
});

?>
