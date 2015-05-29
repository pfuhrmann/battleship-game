<?php

namespace WorldStores\Test\GameEngine;

class DirectedPlacingParams extends BasicPlacingParams
{
    protected $length;
    protected $direction;

    /**
     * @param int       $row       Row where to place
     * @param int       $column    Column where to place
     * @param mixed     $type      Value to place
     * @param int       $length    Length of placing params
     * @param mixed     $extra     Extra data in placement
     * @param Direction $direction Direction of placement
     */
    public function __construct($row, $column, $type, $length, Direction $direction, $extra = null)
    {
        $this->row = $row;
        $this->column = $column;
        $this->type = $type;
        $this->length = $length;
        $this->direction = $direction;
        $this->extra = $extra;
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
