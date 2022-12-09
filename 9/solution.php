<?php

class Day9
{
    /** @var string[] */
    public array $visited = ['0 0'];

    /** @var array<string, int> */
    public array $head = ['x' => 0, 'y' => 0];

    /** @var array<string, int> */

    public array $tail = ['x' => 0, 'y' => 0];

    /** @var array<int, array<string, int>> */
    public array $tails = [
        1 => ['x' => 0, 'y' => 0],
        2 => ['x' => 0, 'y' => 0],
        3 => ['x' => 0, 'y' => 0],
        4 => ['x' => 0, 'y' => 0],
        5 => ['x' => 0, 'y' => 0],
        6 => ['x' => 0, 'y' => 0],
        7 => ['x' => 0, 'y' => 0],
        8 => ['x' => 0, 'y' => 0],
        9 => ['x' => 0, 'y' => 0],
    ];

    /** @var string[] */
    public array $steps;

    /** @var array<string, int[]> */

    public array $map = [
        '0 0' => [0, 0],
        '1 0' => [0, 0],
        '2 0' => [1, 0],
        '0 1' => [0, 0],
        '0 2' => [0, 1],
        '-1 0' => [0, 0],
        '-2 0' => [-1, 0],
        '0 -1' => [0, 0],
        '0 -2' => [0, -1],
        '1 1' => [0, 0],
        '-1 -1' => [0, 0],
        '1 -1' => [0, 0],
        '-1 1' => [0, 0],
        '1 2' => [1, 1],
        '2 1' => [1, 1],
        '-1 -2' => [-1, -1],
        '-2 -1' => [-1, -1],
        '1 -2' => [1, -1],
        '-1 2' => [-1, 1],
        '2 -1' => [1, -1],
        '-2 1' => [-1, 1],
        // For Part 2
        '2 2' => [1, 1],
        '-2 -2' => [-1, -1],
        '2 -2' => [1, -1],
        '-2 2' => [-1, 1],
    ];

    public function __construct()
    {
        $this->steps = explode(PHP_EOL, file_get_contents('data.txt'));
    }

    public function moveHead(string $direction): void
    {
        match ($direction) {
            'R' => $this->head['x']++,
            'L' => $this->head['x']--,
            'U' => $this->head['y']++,
            'D' => $this->head['y']--,
        };
    }

    protected function moveTail(): void
    {
        $xDiff = $this->head['x'] - $this->tail['x'];
        $yDiff = $this->head['y'] - $this->tail['y'];

        [$xMove, $yMove] = $this->map["$xDiff $yDiff"];

        $this->tail['x'] += $xMove;
        $this->tail['y'] += $yMove;

        $this->recordVisited();
    }

    protected function recordVisited(): void
    {
        $value = $this->tail['x'] . ' ' . $this->tail['y'];

        if (!in_array($value, $this->visited)) {
            $this->visited[] = $value;
        }
    }

    public function solveFirst(): int
    {
        foreach ($this->steps as $step) {
            [$direction, $amount] = explode(' ', $step);

            foreach (range(1, $amount) as $move) {
                $this->moveHead($direction);
                $this->moveTail();
            }
        }

        return count($this->visited);
    }

    protected function moveTails(): void
    {
        $previous = 'head';

        foreach ($this->tails as $tail => $positions) {
            if ($previous === 'head') {
                $xDiff = $this->head['x'] - $positions['x'];
                $yDiff = $this->head['y'] - $positions['y'];
            } else {
                $xDiff = $this->tails[$previous]['x'] - $positions['x'];
                $yDiff = $this->tails[$previous]['y'] - $positions['y'];
            }

            [$xMove, $yMove] = $this->map["$xDiff $yDiff"];

            $this->tails[$tail]['x'] += $xMove;
            $this->tails[$tail]['y'] += $yMove;

            if ($tail === 9) {
                $value = $this->tails[$tail]['x'] . ' ' . $this->tails[$tail]['y'];

                if (!in_array($value, $this->visited)) {
                    $this->visited[] = $value;
                }
            }

            $previous = $tail;
        }
    }

    public function solveSecond(): int
    {
        foreach ($this->steps as $step) {
            [$direction, $amount] = explode(' ', $step);

            foreach (range(1, $amount) as $move) {
                $this->moveHead($direction);
                $this->moveTails();
            }
        }

        return count($this->visited);
    }
}

$firstAnswer = (new Day9())->solveFirst();
$secondAnswer = (new Day9())->solveSecond();

echo "1. $firstAnswer\n";
echo "2. $secondAnswer";
