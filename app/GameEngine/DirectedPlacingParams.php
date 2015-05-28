<?php

namespace WorldStores\Test\GameEngine;

class DirectedPlacingParams extends BasicPlacingParams
{
    protected $length;
    protected $direction;

    /**
     * @param int       $row       Row where to place
     * @param int       $column    Column where to place
     * @param mixed     $val       Value to place
     * @param int       $length    Length of placing params
     * @param Direction $direction Direction of placement
     */
    public function __construct($row, $column, $val, $length, Direction $direction)
    {
        $this->row = $row;
        $this->column = $column;
        $this->val = $val;
        $this->length = $length;
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @return Direction
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
