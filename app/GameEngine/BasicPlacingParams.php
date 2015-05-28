<?php

namespace WorldStores\Test\GameEngine;

class BasicPlacingParams
{
    protected $row;
    protected $column;

    /**
     * @param int       $row       Row where to place
     * @param int       $column    Column where to place
     */
    public function __construct($row, $column)
    {
        $this->row = $row;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn()
    {
        return $this->column;
    }
}
