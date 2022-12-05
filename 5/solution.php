<?php

/*
    [S] [C]         [Z]
[F] [J] [P]         [T]     [N]
[G] [H] [G] [Q]     [G]     [D]
[V] [V] [D] [G] [F] [D]     [V]
[R] [B] [F] [N] [N] [Q] [L] [S]
[J] [M] [M] [P] [H] [V] [B] [B] [D]
[L] [P] [H] [D] [L] [F] [D] [J] [L]
[D] [T] [V] [M] [J] [N] [F] [M] [G]
 1   2   3   4   5   6   7   8   9
*/

$original = [
    1 => ['F', 'G', 'V', 'R', 'J', 'L', 'D'],
    2 => ['S', 'J', 'H', 'V', 'B', 'M', 'P', 'T'],
    3 => ['C', 'P', 'G', 'D', 'F', 'M', 'H', 'V'],
    4 => ['Q', 'G', 'N', 'P', 'D', 'M'],
    5 => ['F', 'N', 'H', 'L', 'J'],
    6 => ['Z', 'T', 'G', 'D', 'Q', 'V', 'F', 'N'],
    7 => ['L', 'B', 'D', 'F'],
    8 => ['N', 'D', 'V', 'S', 'B', 'J', 'M'],
    9 => ['D', 'L', 'G'],
];

foreach ($original as $index => $stack) {
    $original[$index] = array_reverse($stack);
}

$stacks = $original;

$actions = explode(PHP_EOL, file_get_contents('data.txt'));

foreach ($actions as $action) {
    // e.g. move 3 from 4 to 6
    preg_match_all('/[0-9]+/', $action, $matches);

    $move = (int)$matches[0][0];
    $from = (int)$matches[0][1];
    $to = (int)$matches[0][2];

    foreach (range(1, $move) as $rep) {
        $removed = array_pop($stacks[$from]);

        $stacks[$to][] = $removed;
    }
}

$firstAnswer = array_reduce($stacks, fn ($carry, $stack) => $carry . end($stack), '');

$stacks = $original;

foreach ($actions as $action) {
    // move 3 from 4 to 6
    preg_match_all('/[0-9]+/', $action, $matches);

    $move = (int)$matches[0][0];
    $from = (int)$matches[0][1];
    $to = (int)$matches[0][2];

    $removed = array_splice($stacks[$from], -$move, $move);

    foreach ($removed as $add) {
        $stacks[$to][] = $add;
    }
}

$secondAnswer = array_reduce($stacks, fn ($carry, $stack) => $carry . end($stack), '');

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
