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
  $test->it("The static class method ComplexNumber::Im() should accept real and complex numbers alike and return their imaginary components", function () use ($test) {
    // Complex number tests
    $z1 = new ComplexNumber(4, 5);
    $z2 = new ComplexNumber(-3.684, 10);
    $z3 = new ComplexNumber(-5, -3.5);
    $z4 = new ComplexNumber(-7.777, -8.166);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 5);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 10);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), -3.5);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), -8.166);
    // Real number tests - Im(z) should return 0 every time
    $test->assert_fuzzy_equals(ComplexNumber::Im(756), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im(0.314), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im(-7.11453), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im(-2), 0);
    // Zero Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im(0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im(0.0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im(new ComplexNumber(0)), 0);
  });
  $test->it("The static class method ComplexNumber::Re() should accept real and complex numbers alike and return their real components", function () use ($test) {
    // Complex number tests
    $z1 = new ComplexNumber(4, 5);
    $z2 = new ComplexNumber(-3.684, 10);
    $z3 = new ComplexNumber(-5, -3.5);
    $z4 = new ComplexNumber(-7.777, -8.166);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), 4);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), -3.684);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -5);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), -7.777);
    // Real number tests - Re(z) should return the number itself every time
    $test->assert_fuzzy_equals(ComplexNumber::Re(756), 756);
    $test->assert_fuzzy_equals(ComplexNumber::Re(0.314), 0.314);
    $test->assert_fuzzy_equals(ComplexNumber::Re(-7.11453), -7.11453);
    $test->assert_fuzzy_equals(ComplexNumber::Re(-2), -2);
    // Zero Tests
    $test->assert_fuzzy_equals(ComplexNumber::Re(0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re(0.0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re(new ComplexNumber(0.0)), 0);
  });
  $test->it("The static class method ComplexNumber::arg() should accept real and complex numbers alike and return their arguments", function () use ($test) {
    // Complex number tests
    $z = new ComplexNumber(24, -7);
    $w = new ComplexNumber(37, -5 * M_PI / 6, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z), -0.283794109208328);
    $test->assert_fuzzy_equals(ComplexNumber::arg($w), -5 * M_PI / 6);
    // Real number tests - positive and negative
    $test->assert_fuzzy_equals(ComplexNumber::arg(12367), 0);
    $test->assert_fuzzy_equals(ComplexNumber::arg(17.3354), 0);
    $test->assert_fuzzy_equals(ComplexNumber::arg(0.002066), 0);
    $test->assert_fuzzy_equals(ComplexNumber::arg(-12367), M_PI);
    $test->assert_fuzzy_equals(ComplexNumber::arg(-17.3354), M_PI);
    $test->assert_fuzzy_equals(ComplexNumber::arg(-0.002066), M_PI);
    // Zero tests - the convention arg(0 + 0i) = 0 should be used (source: WolframAlpha)
    $test->assert_fuzzy_equals(ComplexNumber::arg(0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::arg(0.0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::arg(new ComplexNumber(0.0, 0.0)), 0);
  });
  $test->it("should have a working static class method ComplexNumber::abs()", function () use ($test) {
    // Complex number tests
    $z = new ComplexNumber(69, 1733);
    $w = new ComplexNumber(-0.01232, -3.112);
    $test->assert_fuzzy_equals(ComplexNumber::abs($z), 5 * sqrt(120322));
    $test->assert_fuzzy_equals(ComplexNumber::abs($w), 3.112024386536841);
    // Real number tests
    $test->assert_fuzzy_equals(ComplexNumber::abs(234432), 234432);
    $test->assert_fuzzy_equals(ComplexNumber::abs(445.8876), 445.8876);
    $test->assert_fuzzy_equals(ComplexNumber::abs(0.0013443), 0.0013443);
    $test->assert_fuzzy_equals(ComplexNumber::abs(-234432), 234432);
    $test->assert_fuzzy_equals(ComplexNumber::abs(-445.8876), 445.8876);
    $test->assert_fuzzy_equals(ComplexNumber::abs(-0.0013443), 0.0013443);
    // Zero tests
    $test->assert_fuzzy_equals(ComplexNumber::abs(0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::abs(0.0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::abs(new ComplexNumber(0.0)), 0);
  });
  $test->it("ComplexNumber::Im() should throw with invalid input", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::Im("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::Im("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::Im(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::Im(array(3, 5));
    });
  });
  $test->it("ComplexNumber::Re() should throw with invalid input", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::Re("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::Re("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::Re(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::Re(array(3, 5));
    });
  });
  $test->it("ComplexNumber::arg() should throw with invalid input", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::arg("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::arg("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::arg(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::arg(array(3, 5));
    });
  });
  $test->it("ComplexNumber::abs() should throw with invalid input", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::abs("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::abs("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::abs(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::abs(array(3, 5));
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
  $test->it("should have a working instance method ComplexNumber::subtract()", function () use ($test) {
    $z = new ComplexNumber(7.5, -2.3);
    $z1 = $z->subtract(34.11);
    $z2 = $z->subtract(-5.579);
    $z3 = $z->subtract(new ComplexNumber(-8.8, 2.1));
    $z4 = $z->subtract(new ComplexNumber(-5, -4));
    // Immutability test on z
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 7.5);
    // Test complex numbers "z1" through "z4"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), -26.61);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), -2.3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 13.079);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), -4.4);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), 16.3);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 1.7);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), 12.5);
  });
  $test->it("ComplexNumber::subtract() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->subtract("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->subtract("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->subtract(true);
    });
    $test->expect_error("An array should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->subtract(array(3, 5));
    });
  });
  $test->it("should have an instance method ComplexNumber::minus() which is an alias of ComplexNumber::subtract()", function () use ($test) {
    $z = new ComplexNumber(4, -3); // 4 - 3i
    $w = $z->minus(new ComplexNumber(12, 13)); // (4 - 3i) - (12 + 13i) = -8 - 16i
    $test->assert_fuzzy_equals(ComplexNumber::Im($w), -16);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w), -8);
  });
  $test->it("should have a working instance method ComplexNumber::multiply()", function () use ($test) {
    $z = new ComplexNumber(-7, 4); // -7 + 4i
    $z1 = $z->multiply(4.5);
    $z2 = $z->multiply(-6);
    $z3 = $z->multiply(new ComplexNumber(5 / 2, 7 / 2));
    $z4 = $z->multiply($z->getComplexConjugate());
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 4);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -7);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 18);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), -31.5);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), -24);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 42);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), -14.5);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -31.5);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), 65);
  });
  $test->it("ComplexNumber::multiply() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->multiply("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->multiply("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->multiply(true);
    });
    $test->expect_error("An array should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->multiply(array(3, 5));
    });
  });
  $test->it("ComplexNumber::multiply() should have two aliases, ComplexNumber::times() and ComplexNumber::multipliedBy()", function () use ($test) {
    $z = new ComplexNumber(10, M_PI / 4, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $z1 = $z->times(new ComplexNumber(1 / 5, -M_PI / 2, ComplexNumber::MODULUS_ARGUMENT_FORM));
    $z2 = $z->multipliedBy(new ComplexNumber(1 / 2, M_PI / 3, ComplexNumber::MODULUS_ARGUMENT_FORM));
    $test->assert_fuzzy_equals($z1->getModulus(), 2);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z1), -M_PI / 4);
    $test->assert_fuzzy_equals($z2->getModulus(), 5);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z2), 7 * M_PI / 12);
  });
  $test->it("should have a working instance method ComplexNumber::divide()", function () use ($test) {
    $z = new ComplexNumber(-23, 37); // -23 + 37i
    $z1 = $z->divide(17); // Integer Division (positive)
    $z2 = $z->divide(-3.8); // Float Division (negative)
    $z3 = $z->divide(new ComplexNumber(9, 7)); // Complex Number Division
    $z4 = $z->divide(new ComplexNumber(0, -55)); // Division by Imaginary Number
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 37);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -23);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 37 / 17);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), -23 / 17);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), -185 / 19);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 115 / 19);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 3.8);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), 0.4);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), -23 / 55);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), -37 / 55);
  });
  $test->it("ComplexNumber::divide() should not accept division by zero", function () use ($test) {
    $test->expect_error("Division by zero should throw DivisionByZeroError", function () {
      $z = new ComplexNumber(-4, -13);
      $z->divide(0);
    });
    $test->expect_error("Division by zero should throw DivisionByZeroError (2)", function () {
      $z = new ComplexNumber(-4, -13);
      $z->divide(0.0);
    });
    $test->expect_error("Division by zero should throw DivisionByZeroError (3)", function () {
      $z = new ComplexNumber(-4, -13);
      $z->divide(new ComplexNumber(0));
    });
  });
  $test->it("ComplexNumber::divide() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->divide("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->divide("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->divide(true);
    });
    $test->expect_error("An array should be rejected", function () {
      $z = new ComplexNumber(7.5, -2.3);
      $z->divide(array(3, 5));
    });
  });
  $test->it("ComplexNumber::divide() should have two aliases, ComplexNumber::over() and ComplexNumber::dividedBy()", function () use ($test) {
    $z = new ComplexNumber(10, 3 * M_PI / 4, ComplexNumber::MODULUS_ARGUMENT_FORM);
    $z1 = $z->over(new ComplexNumber(8, M_PI / 2, ComplexNumber::MODULUS_ARGUMENT_FORM));
    $z2 = $z->dividedBy(new ComplexNumber(4, -3 * M_PI / 4, ComplexNumber::MODULUS_ARGUMENT_FORM));
    $test->assert_fuzzy_equals($z1->getModulus(), 1.25);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z1), M_PI / 4);
    $test->assert_fuzzy_equals($z2->getModulus(), 2.5);
    $test->assert_fuzzy_equals(ComplexNumber::arg($z2), -M_PI / 2);
  });
  $test->it("should have a working static class method ComplexNumber::sqrt()", function () use ($test) {
    $z = new ComplexNumber(-7, 24); // -7 + 24i
    $sqrt_z = ComplexNumber::sqrt($z); // sqrt(-7 + 24i) = 3 + 4i
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 24);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -7);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_z), 4);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_z), 3);
    // More tests
    $sqrt_25 = ComplexNumber::sqrt(25); // sqrt(25) = 5 (= 5 + 0i)
    $sqrt_minus_zero_point_one_six = ComplexNumber::sqrt(-0.16); // sqrt(-0.16) = 0.4i
    $sqrt_i = ComplexNumber::sqrt(new ComplexNumber(0, 1)); // sqrt(i) = e^(i * PI / 4)
    $sqrt_w = ComplexNumber::sqrt(new ComplexNumber(-24, -7)); // sqrt(-24 - 7i) = sqrt(2) / 2 - (7 * sqrt(2) / 2)i
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_25), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_25), 5);
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_minus_zero_point_one_six), 0.4);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_minus_zero_point_one_six), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_i), sqrt(2) / 2);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_i), sqrt(2) / 2);
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_w), -7 * sqrt(2) / 2);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_w), sqrt(2) / 2);
    // Zero Test
    $sqrt_0 = ComplexNumber::sqrt(0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($sqrt_0), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($sqrt_0), 0);
  });
  $test->it("ComplexNumber::sqrt() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::sqrt("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::sqrt("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::sqrt(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::sqrt(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::exp()", function () use ($test) {
    $z = new ComplexNumber(2, 3); // 2 + 3i
    $w = new ComplexNumber(-77 / 23, 39 / 27); // -77 / 23 + (39 / 27)i
    $exp_z = ComplexNumber::exp($z);
    $exp_w = ComplexNumber::exp($w);
    // Immutability Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 3);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 2);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w), 39 / 27);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w), -77 / 23);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($exp_z), 1.042743656235904); // source: WolframAlpha
    $test->assert_fuzzy_equals(ComplexNumber::Re($exp_z), -7.315110094901103); // source: WolframAlpha
    $test->assert_fuzzy_equals(ComplexNumber::Im($exp_w), 0.034880413800279); // source: WolframAlpha
    $test->assert_fuzzy_equals(ComplexNumber::Re($exp_w), 0.004430810070815); // source: WolframAlpha
    // Real Number Tests
    $e_cubed = ComplexNumber::exp(3); // Input: Positive Integer
    $e_to_weird_power = ComplexNumber::exp(-3 / 7); // Input: Negative Float
    $test->assert_fuzzy_equals(ComplexNumber::Im($e_cubed), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($e_cubed), exp(3));
    $test->assert_fuzzy_equals(ComplexNumber::Im($e_to_weird_power), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($e_to_weird_power), exp(-3 / 7));
    // Imaginary Number Tests
    $e_to_the_i = ComplexNumber::exp(new ComplexNumber(0, 1));
    $e_to_weird_imaginary_power = ComplexNumber::exp(new ComplexNumber(0, -2.77));
    $test->assert_fuzzy_equals($e_to_the_i->getModulus(), 1);
    $test->assert_fuzzy_equals(ComplexNumber::arg($e_to_the_i), 1);
    $test->assert_fuzzy_equals($e_to_weird_imaginary_power->getModulus(), 1);
    $test->assert_fuzzy_equals(ComplexNumber::arg($e_to_weird_imaginary_power), -2.77);
    // Euler's Identity Test
    $zero = ComplexNumber::exp(new ComplexNumber(0, M_PI))->plus(1); // e^(i * PI) + 1 = 0
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero), 0);
    // Zero Test
    $one = ComplexNumber::exp(0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($one), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one), 1);
  });
  $test->it("ComplexNumber::exp() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::exp("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::exp("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::exp(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::exp(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::log()", function () use ($test) {
    // Base "e" - Real number tests
    $ln_1p75 = ComplexNumber::log(1.75); // Positive float
    $log_minus_3 = ComplexNumber::log(-3); // Negative integer
    $test->assert_fuzzy_equals(ComplexNumber::Im($ln_1p75), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($ln_1p75), log(1.75));
    $test->assert_fuzzy_equals(ComplexNumber::Im($log_minus_3), M_PI);
    $test->assert_fuzzy_equals(ComplexNumber::Re($log_minus_3), log(3));
    // Base "e" - Complex number tests
    $z = ComplexNumber::log(new ComplexNumber(24, 7));
    $w = ComplexNumber::log(new ComplexNumber(-8 / 3, -2));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 0.283794109208328);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 3.218875824868201);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w), -2.498091544796509);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w), 1.203972804325936);
    // Real base - mixed tests
    $three = ComplexNumber::log(8, 2); // log2(8) = 3 + 0i
    $weird = ComplexNumber::log(new ComplexNumber(0, 1), 4); // log4(i)
    $test->assert_fuzzy_equals(ComplexNumber::Im($three), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($three), 3);
    $test->assert_fuzzy_equals(ComplexNumber::Im($weird), 1.133090035456798);
    $test->assert_fuzzy_equals(ComplexNumber::Re($weird), 0);
    // Complex base - complex test
    $ultimate = ComplexNumber::log(new ComplexNumber(24, 7), new ComplexNumber(5, -2)); // Logarithm, base 5 - 2i, exponent 24 + 7i
    $test->assert_fuzzy_equals(ComplexNumber::Im($ultimate), 0.571450787977668);
    $test->assert_fuzzy_equals(ComplexNumber::Re($ultimate), 1.782697634765799);
  });
  $test->it("ComplexNumber::log() should not accept zero values", function () use ($test) {
    $test->expect_error("An exponent of zero should throw an ArithmeticError", function () {
      ComplexNumber::log(0);
    });
    $test->expect_error("An exponent of zero should throw an ArithmeticError (2)", function () {
      ComplexNumber::log(0.0);
    });
    $test->expect_error("An exponent of zero should throw an ArithmeticError (3)", function () {
      ComplexNumber::log(new ComplexNumber(0));
    });
    $test->expect_error("A base of zero should throw an ArithmeticError", function () {
      ComplexNumber::log(new ComplexNumber(3, 4), 0);
    });
    $test->expect_error("A base of zero should throw an ArithmeticError (2)", function () {
      ComplexNumber::log(new ComplexNumber(3, 4), 0.0);
    });
    $test->expect_error("A base of zero should throw an ArithmeticError (3)", function () {
      ComplexNumber::log(new ComplexNumber(3, 4), new ComplexNumber(0.0));
    });
  });
  $test->it("ComplexNumber::log() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::log("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::log("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::log(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::log(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::pow()", function () use ($test) {
    // Real base, real exponent tests
    $n243 = ComplexNumber::pow(3, 5); // 3 ** 5 === 243 (= 243 + 0i)
    $one_eighth = ComplexNumber::pow(2, -3); // 2 ** (-3) === 1 / 8 (= 1 / 8 + 0i)
    $weird_real = ComplexNumber::pow(0.77, -3.65);
    $test->assert_fuzzy_equals(ComplexNumber::Im($n243), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($n243), 243);
    $test->assert_fuzzy_equals(ComplexNumber::Im($one_eighth), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one_eighth), 1 / 8);
    $test->assert_fuzzy_equals(ComplexNumber::Im($weird_real), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($weird_real), 0.77 ** -3.65);
    // Complex base, real exponent tests
    $minus_one = ComplexNumber::pow(new ComplexNumber(0, 1), 2); // i ^ 2 = -1
    $z = ComplexNumber::pow(new ComplexNumber(3, 2), 2.5);
    $w = ComplexNumber::pow(new ComplexNumber(5, 7), -0.33);
    $test->assert_fuzzy_equals(ComplexNumber::Im($minus_one), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($minus_one), -1);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 24.559500865789335);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 2.483763832715803);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w), -0.151676613588827);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w), 0.467574267034797);
    // Real base, complex exponent tests
    $z = ComplexNumber::pow(45, new ComplexNumber(2, -1));
    $w = ComplexNumber::pow(-5.5, new ComplexNumber(1 / 3, 2 / 3));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 1249.656025134110298);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -1593.419222566997869);
    // Complex base, complex exponent test
    $ultimate = ComplexNumber::pow(new ComplexNumber(1, 4), new ComplexNumber(3, 2));
    $test->assert_fuzzy_equals(ComplexNumber::Im($ultimate), 2.488628503694742);
    $test->assert_fuzzy_equals(ComplexNumber::Re($ultimate), 4.272043012751457);
  });
  $test->it("ComplexNumber::pow() should behave as expected for z = 0 + 0i too", function () use ($test) {
    // Zero test - all "w" where Re(w) > 0 should return 0 + 0i
    $zero1 = ComplexNumber::pow(0, 3);
    $zero2 = ComplexNumber::pow(0.0, 0.178);
    $zero3 = ComplexNumber::pow(0, new ComplexNumber(4, -3.6));
    $zero4 = ComplexNumber::pow(0.0, new ComplexNumber(0.33, 7));
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero4), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero4), 0);
    // Zero test - all imaginary "w" (or w = 0 + 0i) should return 1 + 0i
    $one = ComplexNumber::pow(new ComplexNumber(0.0), new ComplexNumber(0, 13));
    $test->assert_fuzzy_equals(ComplexNumber::Im($one), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one), 1);
    $one = ComplexNumber::pow(new ComplexNumber(0.0), new ComplexNumber(0, -77.89));
    $test->assert_fuzzy_equals(ComplexNumber::Im($one), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one), 1);
    $one = ComplexNumber::pow(0, 0.0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($one), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one), 1);
    // Zero test - all "w" such that Re(w) < 0 should throw an error (since complex infinity is not comprehendable)
    $test->expect_error("A result of \"complex infinity\" should throw an error", function () {
      ComplexNumber::pow(0, new ComplexNumber(-4.44, 9.766));
    });
  });
  $test->it("ComplexNumber::pow() should type check its arguments", function () use ($test) {
    $test->expect_error("Strings and booleans should not be accepted", function () {
      ComplexNumber::pow("Hello World", false);
    });
    $test->expect_error("An invalid \"w\" should throw an error even when \"z\" is valid", function () {
      ComplexNumber::pow(new ComplexNumber(3, 5), array(M_PI));
    });
  });
  $test->it("should have a working static class method ComplexNumber::sinh()", function () use ($test) {
    // Real number tests
    $z1 = ComplexNumber::sinh(-10);
    $z2 = ComplexNumber::sinh(-2.33);
    $z3 = ComplexNumber::sinh(-0.027);
    $z4 = ComplexNumber::sinh(0.027);
    $z5 = ComplexNumber::sinh(2.33);
    $z6 = ComplexNumber::sinh(10);
    $test->expect(is_a($z1, "ComplexNumber"));
    $test->expect(is_a($z2, "ComplexNumber"));
    $test->expect(is_a($z3, "ComplexNumber"));
    $test->expect(is_a($z4, "ComplexNumber"));
    $test->expect(is_a($z5, "ComplexNumber"));
    $test->expect(is_a($z6, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z5), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z6), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), -11013.232874703393377);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), -5.090322892976957);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -0.027003280619576);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), 0.027003280619576);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z5), 5.090322892976957);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z6), 11013.232874703393377);
    // Complex Number Tests
    $z = new ComplexNumber(-11, 13.5);
    $w1 = ComplexNumber::sinh($z);
    $w2 = ComplexNumber::sinh(new ComplexNumber(-13.5, 11));
    $w3 = ComplexNumber::sinh(new ComplexNumber(0.83, -0.22));
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 13.5);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -11);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($w1), 24062.951338622665266);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w1), -17810.182047189887626);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w2), -364704.613173419052820);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w2), -1614.088280240106078);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w3), -0.297814477845389);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w3), 0.906252261411138);
    // Zero Tests
    $zero1 = ComplexNumber::sinh(0);
    $zero2 = ComplexNumber::sinh(0.0);
    $zero3 = ComplexNumber::sinh(new ComplexNumber(0.0));
    $test->expect(is_a($zero1, "ComplexNumber"));
    $test->expect(is_a($zero2, "ComplexNumber"));
    $test->expect(is_a($zero3, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero3), 0);
  });
  $test->it("ComplexNumber::sinh() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::sinh("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::sinh("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::sinh(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::sinh(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::cosh()", function () use ($test) {
    // Real number tests
    $z1 = ComplexNumber::cosh(-5);
    $z2 = ComplexNumber::cosh(-0.967);
    $z3 = ComplexNumber::cosh(0.967);
    $z4 = ComplexNumber::cosh(5);
    $test->expect(is_a($z1, "ComplexNumber"));
    $test->expect(is_a($z2, "ComplexNumber"));
    $test->expect(is_a($z3, "ComplexNumber"));
    $test->expect(is_a($z4, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), 74.209948524787844);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 1.505132239831420);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), 1.505132239831420);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), 74.209948524787844);
    // Complex number tests
    $z = new ComplexNumber(2, -6.79);
    $w1 = ComplexNumber::cosh($z);
    $w2 = ComplexNumber::cosh(new ComplexNumber(1.5, 1.5));
    $w3 = ComplexNumber::cosh(new ComplexNumber(-0.01, 0.0019));
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), -6.79);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 2);
    // Computational tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($w1), -1.760459239136031);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w1), 3.289269152603249);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w2), 2.123945581536093);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w2), 0.166402873358505);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w3), -0.0000190003052364);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w3), 1.000048195326960);
    // Zero tests
    $one1 = ComplexNumber::cosh(0);
    $one2 = ComplexNumber::cosh(0.0);
    $one3 = ComplexNumber::cosh(new ComplexNumber(0));
    $test->expect(is_a($one1, "ComplexNumber"));
    $test->expect(is_a($one2, "ComplexNumber"));
    $test->expect(is_a($one3, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($one1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one1), 1);
    $test->assert_fuzzy_equals(ComplexNumber::Im($one2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one2), 1);
    $test->assert_fuzzy_equals(ComplexNumber::Im($one3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($one3), 1);
  });
  $test->it("ComplexNumber::cosh() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::cosh("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::cosh("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::cosh(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::cosh(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::tanh()", function () use ($test) {
    // Real number tests
    $z1 = ComplexNumber::tanh(7);
    $z2 = ComplexNumber::tanh(0.00644);
    $z3 = ComplexNumber::tanh(-0.00644);
    $z4 = ComplexNumber::tanh(-7);
    $test->expect(is_a($z1, "ComplexNumber"));
    $test->expect(is_a($z2, "ComplexNumber"));
    $test->expect(is_a($z3, "ComplexNumber"));
    $test->expect(is_a($z4, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), 0.999998336943945);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 0.006439910971482);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -0.006439910971482);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), -0.999998336943945);
    // Complex number tests
    $z = new ComplexNumber(-2.5, -3.1); // -2.5 - 3.1i
    $w1 = ComplexNumber::tanh($z);
    $w2 = ComplexNumber::tanh(new ComplexNumber(1, 7));
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), -3.1);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -2.5);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($w1), 0.001104816913148);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w1), -0.986659661476993);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w2), 0.254071403315039);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w2), 0.930218727078869);
    // Zero Tests
    $zero1 = ComplexNumber::tanh(0);
    $zero2 = ComplexNumber::tanh(0.0);
    $zero3 = ComplexNumber::tanh(new ComplexNumber(0.0, 0.0));
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero3), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero3), 0);
    // Other Edge Cases
    $edge1 = ComplexNumber::tanh(new ComplexNumber(1, -M_PI / 2));
    $edge2 = ComplexNumber::tanh(new ComplexNumber(-0.5, 3 * M_PI / 2));
    $test->expect(is_a($edge1, "ComplexNumber"));
    $test->expect(is_a($edge2, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($edge1), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Im($edge2), 0);
    $test->assert_fuzzy_equals(ComplexNumber::Re($edge1), 1.313035285499331);
    $test->assert_fuzzy_equals(ComplexNumber::Re($edge2), -2.163953413738653);
    // Extreme Edge Cases - Complex Infinity (should throw ArithmeticError)
    $test->expect_error("A result of \"complex infinity\" should throw an error", function () {
      ComplexNumber::tanh(new ComplexNumber(0, -M_PI / 2));
    });
    $test->expect_error("A result of \"complex infinity\" should throw an error (2)", function () {
      ComplexNumber::tanh(new ComplexNumber(0, 5 * M_PI / 2));
    });
    $test->expect_error("A result of \"complex infinity\" should throw an error (3)", function () {
      ComplexNumber::tanh(new ComplexNumber(0, -7 * M_PI / 2));
    });
  });
  $test->it("ComplexNumber::tanh() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::tanh("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::tanh("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::tanh(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::tanh(array(3, 5));
    });
  });
  $test->it("should have a working static class method ComplexNumber::asin()", function () use ($test) {
    // Real number tests
    $z1 = ComplexNumber::asinh(79);
    $z2 = ComplexNumber::asinh(3.337);
    $z3 = ComplexNumber::asinh(-3.337);
    $z4 = ComplexNumber::asinh(-79);
    $test->expect(is_a($z1, "ComplexNumber"));
    $test->expect(is_a($z2, "ComplexNumber"));
    $test->expect(is_a($z3, "ComplexNumber"));
    $test->expect(is_a($z4, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z1), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z2), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z3), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($z4), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z1), 5.062635088303318, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z2), 1.919949549716096, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z3), -1.919949549716096, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z4), -5.062635088303318, 1e-6);
    // Complex number tests
    $z = new ComplexNumber(-67, 33);
    $w1 = ComplexNumber::asinh($z);
    $w2 = ComplexNumber::asinh(new ComplexNumber(0.234, 21.7));
    // Immutability test on "z"
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 33, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), -67, 1e-6);
    // Computational Tests
    $test->assert_fuzzy_equals(ComplexNumber::Im($w1), 0.457624128995359, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w1), -5.006467284245600, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($w2), 1.560001868972386, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($w2), 3.769986431504500, 1e-6);
    // Zero Tests
    $zero1 = ComplexNumber::asinh(0);
    $zero2 = ComplexNumber::asinh(0.0);
    $zero3 = ComplexNumber::asinh(new ComplexNumber(0));
    $test->expect(is_a($zero1, "ComplexNumber"));
    $test->expect(is_a($zero2, "ComplexNumber"));
    $test->expect(is_a($zero3, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero1), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero1), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero2), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero2), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Im($zero3), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($zero3), 0, 1e-6);
  });
  $test->it("ComplexNumber::asinh() should type check its arguments", function () use ($test) {
    $test->expect_error("A numeric string should not be accepted", function () {
      ComplexNumber::asinh("11.23");
    });
    $test->expect_error("A string is invalid input", function () {
      ComplexNumber::asinh("Hello World");
    });
    $test->expect_error("A boolean should be rejected", function () {
      ComplexNumber::asinh(true);
    });
    $test->expect_error("An array should be rejected", function () {
      ComplexNumber::asinh(array(3, 5));
    });
  });
  $test->it("ComplexNumber::asinh() should have an alias called ComplexNumber::arsinh()", function () use ($test) {
    $z = ComplexNumber::arsinh(3998);
    $test->expect(is_a($z, "ComplexNumber"));
    $test->assert_fuzzy_equals(ComplexNumber::Im($z), 0, 1e-6);
    $test->assert_fuzzy_equals(ComplexNumber::Re($z), 8.986697112609270, 1e-6);
  });
});

?>
