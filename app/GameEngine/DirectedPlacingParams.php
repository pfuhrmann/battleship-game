<?php

namespace WorldStores\Test\GameEngine;

class DirectedPlacingParams extends BasicPlacingParams
{
    protected $length;
    protected $direction;

    /**
     * @param int       $row       Row where to place
     * @param int       $column    Column where to place
     * @param int       $length    Length of placing params
     * @param Direction $direction Direction of placement
     */
    public function __construct($row, $column, $length, Direction $direction)
    {
        $this->length = $length;
        $this->direction = $direction;
        $this->row = $row;
        $this->column = $column;
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
