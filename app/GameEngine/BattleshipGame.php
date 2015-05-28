<?php

namespace WorldStores\Test\GameEngine;

class BattleshipGame
{
    private $board;
    private $shipPlaces;

    const POINT_NO_SHOT = Board::POINT_EMPTY;
    const POINT_MISS = '-';
    const POINT_HIT = 'x';
    const POINT_SHIP = 'y';

    /**
     * Create game with default parameters (10x10)
     *
     * @return BattleshipGame
     */
    public static function initDefaultGame()
    {
        return new BattleshipGame(10, 10);
    }

    /**
     * Create game with custom parameters
     *
     * @param int $rows    Number of rows on the board
     * @param int $columns Number of columns on the board
     *
     * @return BattleshipGame
     */
    public static function initCustomGame($rows, $columns)
    {
        return new BattleshipGame($rows, $columns);
    }

    /**
     * Resume game from saved layout
     *
     * @param array $layout Number of rows on the board
     *
     * @return BattleshipGame
     */
    public static function resumeGame($layout)
    {
        $columns = count($layout);
        $rows = max(array_map('count', $layout));
        $game = new BattleshipGame($rows, $columns);
        $game->board->setLayout($layout);

        return $game;
    }

     /**
     * Create new instance of BattleshipGame
     *
     * @param int $rows    Number of rows on the board
     * @param int $columns Number of columns on the board
     */
    private function __construct($rows, $columns)
    {
        $this->board = new Board($rows, $columns);
        $this->initShips();
    }

    /**
     * Place ships on the board
     */
    private function initShips()
    {
        $this->placeShip(new Battleship, 1);
        $this->placeShip(new Destroyer, 2);
    }

    /**
     * Place the ship randomly on the board
     *
     * @param Ship $ship   Instance of ship to place
     * @param int  $amount Amount of vehicles to place
     */
    private function placeShip(Ship $ship, $amount)
    {
        // Register new ships on the board
        // so we will know when the game ends
        $this->shipPlaces += $ship->getLength() * $amount;

        // Place $amount of ships
        for ($i = 0; $i < $amount; $i++) {
            // Repeat process until we have safely
            // placed the ship on the board
            do {
                $length = $ship->getLength();
                $pars = $this->generateRandomParams();
                $direction = new Direction($pars['direction']);
                $params = new DirectedPlacingParams($pars['row'], $pars['col'], $length, $direction);
                $placed = $this->board->placeValueInDirection($params, self::POINT_SHIP);
            } while (!$placed);
        }
    }

    /**
     * Generate random board placing parameters
     *
     * @return array
     */
    private function generateRandomParams()
    {
        return [
            'direction' => rand(0, 3),
            'row' => rand(0, $this->board->getRows()),
            'col' => rand(0, $this->board->getColumns()),
        ];
    }

    /**
     * Generate game board layout
     *  from ordinary layout
     *
     * @return array
     */
    public function generateGameLayout()
    {
        $board = $this->board;
        $board->loopLayout(function($val, $row, $col) use (&$layout) {
            if ($val === self::POINT_NO_SHOT || $val === self::POINT_SHIP) {
                $layout[$row][$col] = '.';
            } else {
                $layout[$row][$col] = $val;
            }
        });

        return $this->makeGamingEdges($layout, $board->getRows(), $board->getColumns());
    }

    /**
     * Generate revealed Battleship board layout
     *  from ordinary bard layout (help function)
     *
     * @return array
     */
    public function generateRevealedGameLayout()
    {
        $board = $this->board;
        $board->loopLayout(function($val, $row, $col) use (&$layout) {
            if (in_array($val, [self::POINT_SHIP, self::POINT_HIT], true)) {
                $layout[$row][$col] = self::POINT_HIT;
            } else if (in_array($val, [self::POINT_MISS, self::POINT_NO_SHOT], true)) {
                $layout[$row][$col] = '';
            }
        });

        return $this->makeGamingEdges($layout, $board->getRows(), $board->getColumns());
    }

    /**
     * Check if all ships on the gaming
     * board are sunk (player wins)
     *
     * @return bool True if all ships are sunk
     */
    public function allShipsSunk()
    {
        $sunk = 0;
        $this->board->loopLayout(function($val) use (&$sunk) {
            if ($val === self::POINT_HIT) {
                $sunk++;
            }
        });

        return ($sunk === $this->shipPlaces);
    }

    /**
     * Register player's game movement (shot)
     *
     * @param string $location Location entered in format 'A5'
     * @return string Message after movement
     */
    public function registerShot($location)
    {
        if (empty($location)) {
            return '';
        }

        list($row, $col) = $this->parseUserLocation($location);
        $layout = $this->getBoardLayout();
        if (!isset($layout[$row][$col])) {
            return '** Input Error **';
        }

        $val = $layout[$row][$col];
        if ($val !== self::POINT_SHIP && $val !== self::POINT_HIT) {
            $placingVal = self::POINT_MISS;
            $message = '** Miss **';
        } else {
            $placingVal = self::POINT_HIT;
            $message = '** Hit **';
        }

        $this->board->placeValue(new BasicPlacingParams($row, $col), $placingVal);

        return $message;
    }

    /**
     * Parse shot location from user string
     *  into row, col integer parameters
     *
     * @param $location
     * @return array|bool false if string not in right format
     */
    private function parseUserLocation($location)
    {
        if (strlen($location) !== 2) {
            return false;
        }

        list($row, $col) = str_split($location);
        // Get number from letter (0 based)
        // i.e. A = 0, B = 1, C = 2, etc.
        $row = ord(strtolower($row)) - 97;

        return [$row, (int) $col];
    }

    /**
     * Create edges around board for easier user navigation
     *
     * @param array $layout
     * @param int $rows
     * @param int $cols
     * @return array
     */
    private function makeGamingEdges($layout, $rows, $cols)
    {
        // Make top gaming edges
        $top = range(0, $cols - 1);
        array_unshift($layout, $top);

        // Make side gaming edges
        $alphas = range('A', 'Z');
        for ($row = 0; $row <  $rows + 1; $row++) {
            if ($row == 0) {
                array_unshift($layout[$row], ' ');
            } else {
                array_unshift($layout[$row], $alphas[$row - 1]);
            }
        }

        return $layout;
    }

    /**
     * Get basic board layout
     *
     * @return array
     */
    public function getBoardLayout()
    {
        return $this->board->getLayout();
    }
}
