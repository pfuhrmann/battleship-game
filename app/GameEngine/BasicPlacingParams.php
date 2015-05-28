<?php

namespace WorldStores\Test\GameEngine;

class BasicPlacingParams
{
    protected $row;
    protected $column;
    protected $val;

    /**
     * @param int   $row     Row where to place
     * @param int   $column  Column where to place
     * @param mixed $val     Value to place
     */
    public function __construct($row, $column, $val)
    {
        $this->row = $row;
        $this->column = $column;
        $this->val = $val;
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

    /**
     * @return mixed
     */
    public function getVal()
    {
        return $this->val;
    }
}
