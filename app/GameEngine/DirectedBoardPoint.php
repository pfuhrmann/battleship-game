<?php

namespace WorldStores\Test\GameEngine;

class DirectedBoardPoint extends BoardPoint
{
    protected $type = 'direct';
    private $neighbourPointA;
    private $neighbourPointB;

    /**
     * @param $val
     * @param DirectedBoardPoint $neighbourPointA
     * @param DirectedBoardPoint $neighbourPointB
     */
    public function __construct($val, DirectedBoardPoint $neighbourPointA, DirectedBoardPoint $neighbourPointB)
    {
        $this->val = $val;
        $this->neighbourPointA = $neighbourPointA;
        $this->neighbourPointB = $neighbourPointB;
    }

    /**
     * @return Direction
     */
    public function getDirection()
    {
        return $this->direction;
    }
}
