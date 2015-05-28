<?php

namespace WorldStores\Test\GameEngine;

class BoardPoint
{
    protected $type;
    protected $val;

    /**
     * @param $val
     * @param $type
     */
    public function __construct($val, $type)
    {
        $this->val = $val;
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
