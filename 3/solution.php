<?php

require __DIR__ . '/../vendor/autoload.php';

$data = explode(PHP_EOL, file_get_contents('data.txt'));

$values = collect(array_merge(range('a', 'z'), range('A', 'Z')))->mapWithKeys(fn ($letter, $i) => [$letter => ++$i]);

$firstAnswer = collect($data)
    ->map(function ($string) {
        $length = strlen($string) / 2;

        $splits = str_split($string, $length);

        return array_unique(
            array_intersect(
                ...array_map(fn ($split) => str_split($split), $splits)
            )
        );
    })
    ->flatten()
    ->map(fn ($letter) => $values[$letter])
    ->sum();

$secondAnswer = collect(array_chunk($data, 3))
    ->map(function ($string) {
        return array_unique(
            array_intersect(
                ...array_map(fn ($split) => str_split($split), $string)
            )
        );
    })
    ->flatten()
    ->map(fn ($letter) => $values[$letter])
    ->sum();

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
