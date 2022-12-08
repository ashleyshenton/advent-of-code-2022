<?php

$leftToRight = array_map(fn ($row) => array_map('intval', str_split($row)), explode(PHP_EOL, file_get_contents('data.txt')));

$topToBottom = [];

foreach (explode(PHP_EOL, file_get_contents('data.txt')) as $row) {
    foreach (str_split($row) as $index => $tree) {
        $topToBottom[$index][] = (int)$tree;
    }
}

$rightToLeft = array_map(fn ($row) => array_reverse($row, true), $leftToRight);
$bottomToTop = array_map(fn ($col) => array_reverse($col, true), $topToBottom);

$views = [
    'left' => $leftToRight,
    'top' => $topToBottom,
    'right' => $rightToLeft,
    'bottom' => $bottomToTop,
];

$visibleTrees = array_fill(0, count($leftToRight), array_fill(0, count($leftToRight[0]), 0));

foreach ($views as $direction => $view) {
    foreach ($view as $rowIndex => $row) {
        $highestTree = -1;

        foreach ($row as $treeIndex => $tree) {
            $rowIndexToSet = in_array($direction, ['left', 'right']) ? $rowIndex : $treeIndex;
            $treeIndexToSet = in_array($direction, ['left', 'right']) ? $treeIndex : $rowIndex;

            // Tree is already visible
            if ($visibleTrees[$rowIndexToSet][$treeIndexToSet]) {
                if ($tree > $highestTree) {
                    $highestTree = $tree;
                }

                continue;
            }

            if ($tree > $highestTree) {
                $visibleTrees[$rowIndexToSet][$treeIndexToSet] = 1;

                $highestTree = $tree;
            }
        }
    }
}

$firstAnswer = array_reduce($visibleTrees, fn ($carry, $row) => $carry + array_sum($row), 0);

$secondAnswer = 0;

foreach ($leftToRight as $rowIndex => $row) {
    foreach ($row as $treeIndex => $tree) {
        $leftRange = $treeIndex === 0 ? [] : range($treeIndex - 1, 0);
        $topRange = $rowIndex === 0 ? [] : range($rowIndex - 1, 0);
        $rightRange = $treeIndex === 98 ? [] : range($treeIndex + 1, 98);
        $bottomRange = $rowIndex === 98 ? [] : range($rowIndex + 1, 98);

        $left = 0;
        $top = 0;
        $right = 0;
        $bottom = 0;

        foreach ($leftRange as $range) {
            $left++;
            if ($row[$range] >= $tree) {
                break;
            }
        }


        foreach ($topRange as $range) {
            $top++;
            if ($leftToRight[$range][$treeIndex] >= $tree) {
                break;
            }
        }

        foreach ($rightRange as $range) {
            $right++;
            if ($row[$range] >= $tree) {
                break;
            }
        }

        foreach ($bottomRange as $range) {
            $bottom++;
            if ($leftToRight[$range][$treeIndex] >= $tree) {
                break;
            }
        }

        $value = $left * $right * $top * $bottom;

        if ($value > $secondAnswer) {
            $secondAnswer = $value;
        }
    }
}

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
