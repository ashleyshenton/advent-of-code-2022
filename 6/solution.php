<?php
function getMarker(int $markerLength): ?int
{
    $contents = file_get_contents('data.txt');

    for ($i = 0; $i < strlen($contents); $i++) {
        $snip = substr($contents, $i, $markerLength);

        $uniqueLetters = array_unique(str_split($snip));

        if (count($uniqueLetters) === $markerLength) {
            return $i + $markerLength;
        }
    }

    return null;
}

$firstAnswer = getMarker(4);
$secondAnswer = getMarker(14);

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
