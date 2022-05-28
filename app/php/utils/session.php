<?php

namespace session {

    session_start();

    if (!check_session()) {
        init_session();
    }

    function init_session(): void
    {
        $_SESSION['initialized'] = true;
        $_SESSION['login'] = '';
        $_SESSION['loggedIn'] = false;
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
}