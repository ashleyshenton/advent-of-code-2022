<?php

// A = Rock
// B = Paper
// C = Scissors

// X = Rock 1
// Y = Paper 2
// Z = Scissors 3

// Lost 0
// Draw 3
// Win 6

$scores = [
    'A X' => 1 + 3,
    'A Y' => 2 + 6,
    'A Z' => 3 + 0,
    'B X' => 1 + 0,
    'B Y' => 2 + 3,
    'B Z' => 3 + 6,
    'C X' => 1 + 6,
    'C Y' => 2 + 0,
    'C Z' => 3 + 3,
];

$results = explode(PHP_EOL, file_get_contents('data.txt'));

$firstAnswer = array_reduce($results, fn ($carry, $result) => $carry + $scores[$result], 0);

// X Lose
// Y Draw
// Z Win

$requiredResults = [
    'A X' => 'A Z',
    'A Y' => 'A X',
    'A Z' => 'A Y',
    'B X' => 'B X',
    'B Y' => 'B Y',
    'B Z' => 'B Z',
    'C X' => 'C Y',
    'C Y' => 'C Z',
    'C Z' => 'C X',
];

$secondAnswer = array_reduce($results, fn ($carry, $result) => $carry + $scores[$requiredResults[$result]], 0);

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
