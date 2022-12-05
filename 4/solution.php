<?php

require __DIR__ . '/../vendor/autoload.php';

$contents = explode(PHP_EOL, file_get_contents('data.txt'));

$values = array_map(function ($value) {
    $ranges = [];

    foreach (explode(',', $value) as $range) {
        $ranges[] = range(str()->before($range, '-'), str()->after($range, '-'));
    }

    return $ranges;
}, $contents);

$fullOverlap = array_map(function ($ranges) {
    $amount = min(count($ranges[0]), count($ranges[1]));

    return count(array_intersect($ranges[0], $ranges[1])) === $amount
        ? 1
        : 0;
}, $values);

$firstAnswer = array_sum($fullOverlap);

$anyOverlap = array_map(function ($ranges) {
    return array_intersect($ranges[0], $ranges[1])
        ? 1
        : 0;
}, $values);

$secondAnswer = array_sum($anyOverlap);

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
