<?php

namespace api {
    require('../utils/utils.php');

    use function utils\request_method_filter;
    use function utils\send_error;

    request_method_filter('POST');
    process_request();

    function process_request(): void
    {
        $req = get_json_request();
        if (!check_request($req)) {
            send_error(400, 'Bad formed request structure');
            exit();
        }
        $response = array();
        switch ($req['api']) {
            case 'game':
                require('handlers/game.php');
                $response = game\handle_request($req);
                break;
        }
        send_json_response($response);
    }

    function send_json_response(array $response): void
    {
        $text = json_encode($response, JSON_UNESCAPED_UNICODE);
        header('Content-Type: application/json; charset=UTF-8');
        echo($text);
    }

    function check_request(array $request): bool
    {
        $keys = array_key_exists('api', $request) &&
            array_key_exists('action', $request) &&
            array_key_exists('content', $request);
        /* if (!$keys) {
             return false;
         }*/
        $types = is_string($request['api']) &&
        is_string($request['action']) &&
        is_array($request['content']);
        return $types;
    }

    function get_json_request(): array
    {
        $text = file_get_contents('php://input');
        $req = json_decode($text, true, 512, JSON_UNESCAPED_UNICODE);
        if ($req === null) {
            send_error(400, 'Error parsing json');
            exit();
        }
        return $req;
    }
}
