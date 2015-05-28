<?php

namespace WorldStores\Test\GameEngine;

class Board
{
    private $rows;
    private $columns;
    private $layout;

    const POINT_EMPTY = -1;

    /**
     * Create new instance of Board
     *
     * @param int $rows    Number of rows on the board
     * @param int $columns Number of columns on the board
     */
    public function __construct($rows, $columns)
    {
        $this->rows = $rows;
        $this->columns = $columns;
        $this->initBoard();
    }

    /**
     * Initialize empty gaming board
     *  - Empty board is 2D array
     *    with all values set to -1 (int)
     *  Example: |-1|-1|-1|
     *           |-1|-1|-1|
     *           |-1|-1|-1|
     */
    private function initBoard()
    {
        $board = [];

        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {
                $board[$row][$column] = self::POINT_EMPTY;
            }
        }

        $this->layout = $board;
    }

    /**
     * Place value on single square
     *
     * @param BasicPlacingParams $params Simple board placing parameters
     * @param int                $val    Value to place
     *
     * @return bool True if original value was empty
     */
    public function placeValue(BasicPlacingParams $params, $val)
    {
        $row = $params->getRow();
        $col = $params->getColumn();


        if ($this->layout[$row][$col] === self::POINT_EMPTY) {
            $this->layout[$row][$col] = $val;

            return true;
        }
        $this->layout[$row][$col] = $val;

        return false;
    }

    /**
     * Place value on number of squares in specific directions
     *  - Values cannot be override and placement
     *    cannot be outside of boundaries
     *
     * @param DirectedPlacingParams $params   Directed board placing parameters
     * @param int                    $placeVal Value to place
     *
     * @return bool True if placement algo passes
     */
    public function placeValueInDirection(DirectedPlacingParams $params, $placeVal)
    {
        $direction = $params->getDirection();
        $rowStart = $params->getRow();
        $colStart = $params->getColumn();
        $layoutCopy = $this->layout; // Copy of layout to allow for reverts
        $process = false; // Holding status of placement algorithm process

        // We will have to check array values in specific
        //  direction to know if we can make placement
        for ($i = 0; $i < $params->getLength(); $i++) {
            list($pCol, $pRow) = $this->calculatePosition($direction, $rowStart, $colStart, $i);

            // Check if we are not out of board
            if (!isset($layoutCopy[$pRow][$pCol])) {
                $process = false;
                break;
            }

            $val = $layoutCopy[$pRow][$pCol];
            // Check if point is already occupied
            if ($val !== self::POINT_EMPTY) {
                $process = false;
                break;
            }

            // Make point occupied
            $layoutCopy[$pRow][$pCol] = $placeVal;
            // Placing process OK
            $process = true;
        }

        // All OK, now we can update board layout
        if ($process) {
            $this->layout = $layoutCopy;
        }

        return $process;
    }

    /**
     * Calculate point position on the board based
     *  on movement and current iteration count
     *
     * @param Direction $direction Direction of movement
     * @param int       $row       Starting point row
     * @param int       $col       Starting point column
     * @param int       $i         Current iteration count
     *
     * @return array Calculated row and column
     */
    public function calculatePosition(Direction $direction, $row, $col, $i)
    {
        // Vertical placement
        if ($direction->isVertical()) {
            if ($direction->isNorth()) {
                $row = $row - $i; // Loop up
            } else {
                $row = $row + $i; // Loop down
            }
        }

        // Horizontal placement
        if ($direction->isHorizontal()) {
            if ($direction->isWest()) {
                $col = $col - $i; // Loop left
            } else {
                $col = $col + $i; // Loop right
            }
        }

        return [$col, $row];
    }

    /**
     * Execute callback function on every point
     *  on the board
     *
     * @param $callback
     */
    public function loopLayout($callback) {
        for ($row = 0; $row < $this->getRows(); $row++) {
            for ($col = 0; $col < $this->getColumns(); $col++) {
                $val = $this->layout[$row][$col];
                $callback($val, $row, $col);
            }
        }
    }

    /**
     * Get number of rows on the gaming board
     *
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
     * Get number of columns on the gaming board
     *
     * @return int
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get simple board layout (without edges)
     *
     * @return array
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param mixed $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }
}
