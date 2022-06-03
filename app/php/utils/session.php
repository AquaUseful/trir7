<?php

namespace utils\session {
    require_once('utils.php');
    require_once('../game/game.php');
    use game;
    use utils;

    session_start();

    if (!check_session()) {
        init_session();
    }

    function init_session(): void
    {
        $_SESSION['initialized'] = true;
        $_SESSION['login'] = '';
        $_SESSION['loggedIn'] = false;
        $_SESSION['game'] = new game\GameState();
    }

    function check_session(): bool
    {
        return (array_key_exists('initialized', $_SESSION) && $_SESSION['initialized']);
    }

    function deinit_session(): void
    {
        $_SESSION = array();
        session_destroy();
    }

    function filter_logged_in(): void
    {
        if (!$_SESSION['loggedIn']) {
            utils\utils\send_error(403, 'Log in to access this page');
            exit();
        }
    }
}
