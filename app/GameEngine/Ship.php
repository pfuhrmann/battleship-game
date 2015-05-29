<?php

namespace WorldStores\Test\GameEngine;

class Ship
{
    protected $length;
    protected $type;

    /**
     * @return int Length of the ship
     */
    public function getLength()
    {
        return $this->length;
    }


    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
