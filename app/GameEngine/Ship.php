<?php

namespace WorldStores\Test\GameEngine;

class Ship
{
    protected $length;

    /**
     * @return int Length of the ship
     */
    public function getLength()
    {
        return $this->length;
    }
}
