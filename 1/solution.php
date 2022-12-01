<?php

require __DIR__ . '/../vendor/autoload.php';

$results = collect(explode(PHP_EOL, file_get_contents('data.csv')))->reduce(
    function ($carry, $value) {
        if ($value === '') {
            $carry[] = 0;

            return $carry;
        }

        $lastKey = key(array_slice($carry, -1, 1, true));

        $carry[$lastKey] += $value;

        return $carry;
    },
    [0]
);

$firstAnswer = max($results);

rsort($results, SORT_NUMERIC);

$secondAnswer = $results[0] + $results[1] + $results[2];

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
