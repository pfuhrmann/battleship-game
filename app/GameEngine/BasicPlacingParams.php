<?php

namespace WorldStores\Test\GameEngine;

class BasicPlacingParams
{
    protected $row;
    protected $column;
    protected $type;
    protected $extra;

    /**
     * @param int   $row     Row where to place
     * @param int   $column  Column where to place
     * @param mixed $type    Type of placement
     * @param mixed $extra   Extra data in placement
     */
    public function __construct($row, $column, $type, $extra = null)
    {
        $this->row = $row;
        $this->column = $column;
        $this->type = $type;
        $this->extra = $extra;
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getExtra()
    {
        return $this->extra;
    }
}
