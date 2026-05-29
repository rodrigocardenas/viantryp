<?php

$regex = '/^(?=.*[A-Z])(?=.*[^A-Za-z0-9])/';

$cases = [
    'Aa1!' => true,      // 4 chars but regex should match (length checked by min:8 in laravel)
    'password!' => false, // no uppercase
    'Password123' => false, // no symbol
    'Password!' => true,  // valid
    'Password.' => true,  // valid (dot is a symbol)
    'P_ssword1' => true,  // valid (underscore is a symbol)
    'Password-1' => true, // valid (hyphen is a symbol)
    'Abcdefg#' => true,   // 8 chars, 1 uppercase, 1 symbol -> valid
    'abcdefgh' => false,  // no uppercase, no symbol
    'ABCDEFGH' => false,  // no symbol
];

echo "Testing regex: $regex\n\n";
$all_passed = true;
foreach ($cases as $password => $expected) {
    $matched = preg_match($regex, $password) === 1;
    $status = ($matched === $expected) ? "PASSED" : "FAILED";
    if ($matched !== $expected) {
        $all_passed = false;
    }
    echo sprintf("Password: '%s' | Expected: %s | Matched: %s | Result: %s\n",
        $password,
        $expected ? "true" : "false",
        $matched ? "true" : "false",
        $status
    );
}

if ($all_passed) {
    echo "\nALL TESTS PASSED! The regex is 100% correct.\n";
} else {
    echo "\nSOME TESTS FAILED!\n";
}
