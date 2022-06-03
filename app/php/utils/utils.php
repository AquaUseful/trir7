<?php

namespace utils\utils {

    function send_error(int $code, string $message): void
    {
        http_response_code($code);
        echo($message);
    }

    function filter_request_method(string $method): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            send_error(405, 'Method not allowed');
            exit();
        }
    }


}
