<?php

namespace WorldStores\Test\GameEngine;

class Direction
{
    private $val;

    const DIR_NORTH = 0;
    const DIR_EAST = 1;
    const DIR_SOUTH = 2;
    const DIR_WEST = 3;

    /**
     * Create new instance of Direction
     *  - direction is integer {0..3}
     *  - 4 cardinal directions clockwise i.e.:
     *       0 => N
     *  3 => W    1 => E
     *       2 => S
     *
     * @param $val
     */
    public function __construct($val)
    {
        $this->val = $val;
    }

    /**
     * Return true if direction is North
     *
     * @return bool
     */
    public function isNorth()
    {
        return ($this->val === self::DIR_NORTH);
    }

    /**
     * Return true if direction is East
     *
     * @return bool
     */
    public function isEast()
    {
        return ($this->val === self::DIR_EAST);
    }

    /**
     * Return true if direction is East
     *
     * @return bool
     */
    public function isSouth()
    {
        return ($this->val === self::DIR_SOUTH);
    }

    /**
     * Return true if direction is West
     *
     * @return bool
     */
    public function isWest()
    {
        return ($this->val === self::DIR_WEST);
    }

    /**
     * Return true if direction is horizontal
     *
     * @return bool
     */
    public function isHorizontal()
    {
        return ($this->isWest() || $this->isEast());
    }

    /**
     * Return true if direction is vertical
     *
     * @return bool
     */
    public function isVertical()
    {
        return ($this->isNorth() || $this->isSouth());
    }
}
