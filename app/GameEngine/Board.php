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
     *  - Empty board is 2D array with
     *     all point type set to -1 (int)
     *  Example: |-1|-1|-1|
     *           |-1|-1|-1|
     *           |-1|-1|-1|
     */
    private function initBoard()
    {
        $layout = [];

        for ($row = 0; $row < $this->rows; $row++) {
            for ($column = 0; $column < $this->columns; $column++) {
                $layout[$row][$column]['type'] = self::POINT_EMPTY;
            }
        }

        $this->layout = $layout;
    }

    /**
     * Place value on single square
     *
     * @param BasicPlacingParams $params Simple board placing parameters
     *
     * @return bool True if original value was empty
     */
    public function placeValue(BasicPlacingParams $params)
    {
        $row = $params->getRow();
        $col = $params->getColumn();
        $this->setPointType($params->getType(), $row, $col);

        if ($this->fetchPointType($row, $col) === self::POINT_EMPTY) {
            return true;
        }

        return false;
    }

    /**
     * Place value on number of squares in specific directions
     *  - Values cannot be override and placement
     *    cannot be outside of boundaries
     *
     * @param DirectedPlacingParams $params   Directed board placing parameters
     *
     * @return bool True if placement algorithm passes
     */
    public function placeValueInDirection(DirectedPlacingParams $params)
    {
        $direction = $params->getDirection();
        $rowStart = $params->getRow();
        $colStart = $params->getColumn();
        $layoutCopy = $this->layout; // Copy of layout to allow reverts
        $process = false; // Holding status of placement algorithm process

        // We will have to check array values in specific
        //  direction to know if we can make placement
        for ($i = 0; $i < $params->getLength(); $i++) {
            list($col, $row) = $this->calculatePosition($direction, $rowStart, $colStart, $i);

            // Check if we are not out of board
            if (!$this->fetchPointType($row, $col)) {
                $process = false;
                break;
            }

            $val = $this->fetchPointType($row, $col);
            // Check if point is already occupied
            if ($val !== self::POINT_EMPTY) {
                $process = false;
                break;
            }

            // Make point occupied
            $this->setPointType($params->getType(), $row, $col);
            if ($params->getExtra() !== null) {
                $this->setPointExtra($params->getExtra(), $row, $col);
            }

            // Placing process OK
            $process = true;
        }

        // Placement not OK we have to revert layout
        if (!$process) {
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
                $val = $this->fetchPointType($row, $col);
                $callback($val, $row, $col);
            }
        }
    }

    /**
     * @return int
     */
    public function getRows()
    {
        return $this->rows;
    }

    /**
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
     * @param array $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    /**
     * Fetch specific point type from the
     *  board layout based on row, col location
     *
     * @param int $row    Fetching point row
     * @param int $col    Fetching point column
     * @return mixed/bool Fetched value otherwise false if point does not exist
     */
    public function fetchPointType($row, $col)
    {
        if (isset($this->layout[$row][$col])) {
            return $this->layout[$row][$col]['type'];
        }

        return false;
    }

    /**
     * Fetch specific point extra data from the
     *  board layout based on row, col location
     *
     * @param int $row    Fetching point row
     * @param int $col    Fetching point column
     * @return mixed/bool Fetched value otherwise false if point does not exist
     */
    public function fetchPointExtra($row, $col)
    {
        if (isset($this->layout[$row][$col])) {
            return $this->layout[$row][$col]['extra'];
        }

        return false;
    }

    /**
     * Set the type of point on the board
     *  in the specific location
     *
     * @param mixed $val Value to be set
     * @param int   $row Setting point row
     * @param int   $col Setting point column
     */
    public function setPointType($val, $row, $col) {
        $this->layout[$row][$col]['type'] = $val;
    }

    /**
     * Set the point's extra data on the
     * board on the specific location
     *
     * @param mixed $extra Value to be set
     * @param int   $row Setting point row
     * @param int   $col Setting point column
     */
    public function setPointExtra($extra, $row, $col) {
        $this->layout[$row][$col]['extra'] = $extra;
    }
}
