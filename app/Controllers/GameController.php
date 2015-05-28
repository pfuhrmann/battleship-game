<?php

namespace WorldStores\Test\Controllers;

use Twig_Environment;
use WorldStores\Test\GameEngine\BattleshipGame;

class GameController
{
    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @param Twig_Environment $twig
     */
    public function __construct(Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * Display game
     *  GET /  HTTP/1.1
     * @return string
     */
    public function getIndex()
    {
        $game = $this->getGame();

        return $this->render('index.html', [
            'layout' => $game->generateGameLayout(),
        ]);
    }

    /**
     * Register hit
     *  POST /  HTTP/1.1
     * @return string
     */
    public function postIndex()
    {
        $position = $_REQUEST['pos'];
        $game = $this->getGame();

        $message = ''; $messageWin = '';
        if ($position === 'show') {
            $layout = $game->generateRevealedGameLayout();
        } else {
            $message = $game->registerShot($position);
            $_SESSION['shots']++; // Increase number of shots fired

            if ($game->allShipsSunk()) {
                $messageWin = 'Well done! You completed the game in '. $_SESSION['shots'] .' shots.';
            }

            $layout = $game->generateGameLayout();
            // Board layout changed so we must save it
            $_SESSION['layout'] = $game->getBoardLayout();
        }

        return $this->render('index.html', [
            'layout' => $layout,
            'message' => $message,
            'messageWin' => $messageWin,
        ]);
    }

    /**
     * Reset game
     *  POST /reset  HTTP/1.1
     */
    public function postReset()
    {
        session_destroy();
        header('Location: /');
    }

    /**
     * Render Twig template
     *
     * @param string $template Template path
     * @param array  $options  Option delivered to template
     * @return string
     */
    private function render($template, array $options)
    {
        $template = $this->twig->loadTemplate($template.'.twig');

        return $template->render($options);
    }

    /**
     * Get game instance
     *
     * @return BattleshipGame
     */
    private function getGame()
    {
        if (!isset($_SESSION['layout'])) {
            // Start new game
            $game = BattleshipGame::initCustomGame(10, 10);
            $_SESSION['layout'] = $game->getBoardLayout();
            $_SESSION['shots'] = 0; // Shots fired counter
        } else {
            $game = BattleshipGame::resumeGame($_SESSION['layout']);
        }

        return $game;
    }
}
