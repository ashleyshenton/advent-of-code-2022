<?php

use Illuminate\Support\Str;

require __DIR__ . '/../vendor/autoload.php';

$contents = explode(PHP_EOL, file_get_contents('data.txt'));

$structure = [];
$path = [];

foreach ($contents as $command) {
    // Change directory
    if (str_starts_with($command, '$ cd ')) {
        $dir = Str::after($command, '$ cd ');

        if ($dir === '..') {
            array_pop($path);

            continue;
        }

        $path[] = $dir;

        data_set($structure, implode('.', $path), []);

        continue;
    }

    // List files
    if (str_starts_with($command, '$ ls')) {
        continue;
    }

    // A listed directory
    if (str_starts_with($command, 'dir ')) {
        $dir = Str::after($command, 'dir ');

        data_set($structure, array_merge($path, [$dir]), []);
    }

    // A listed file
    if (preg_match('/^\d+/', $command, $matches)) {
        $size = $matches[0];

        $file = Str::after($command, "$size ");

        data_set($structure, array_merge($path, [$file]), (int)$size);
    }
}
function firstAnswer(int $add = null): int
{
    static $sum = 0;

    if ($add !== null) {
        $sum += $add;
    }

    return $sum;
}

function calculateFirstAnswer(array $dir): int
{
    $files = array_filter($dir, fn ($type) => is_int($type));
    $directories = array_filter($dir, fn ($type) => is_array($type));

    $fileSize = array_sum($files);
    $directoriesSize = array_reduce($directories, fn (int $carry, array $directory) => $carry + calculateFirstAnswer($directory), 0);

    $size = $fileSize + $directoriesSize;

    if ($size <= 100_000) {
        firstAnswer($size);
    }

    return $size;
}

function usedSpace(int $add = null): int
{
    static $totalVal = 0;

    if ($add !== null) {
        $totalVal += $add;
    }

    return $totalVal;
}

function spaceRequired(): int
{
    $availableSpace = 70_000_000 - usedSpace();

    return 30_000_000 - $availableSpace;
}

function calculateUsedSpace(array $dir): void
{
    $files = array_filter($dir, fn ($type) => is_int($type));
    $directories = array_filter($dir, fn ($type) => is_array($type));

    array_reduce($directories, fn (int $carry, array $directory) => $carry + calculateUsedSpace($directory), 0);

    usedSpace(array_sum($files));
}

function secondAnswer(int $newValue = null): int
{
    static $smallest = 70_000_000;

    if ($newValue !== null) {
        return $smallest = $newValue;
    }

    return $smallest;
}

function calculateSecondAnswer(array $dir): int
{
    $files = array_filter($dir, fn ($type) => is_int($type));
    $directories = array_filter($dir, fn ($type) => is_array($type));

    $fileSize = array_sum($files);
    $directoriesSize = array_reduce($directories, fn (int $carry, array $directory) => $carry + calculateSecondAnswer($directory), 0);

    $size = $fileSize + $directoriesSize;

    if ($size >= spaceRequired() && $size < secondAnswer()) {
        secondAnswer($size);
    }

    return $size;
}

calculateFirstAnswer($structure['/']);
calculateUsedSpace($structure);
calculateSecondAnswer($structure['/']);

$firstAnswer = firstAnswer();
$secondAnswer = secondAnswer();

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
