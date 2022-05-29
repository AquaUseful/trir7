<?php
namespace api\page {
require_once('../page/render.php');
require_once('../page/encode.php');
require_once('../utils/utils.php');
require_once('api.php');
use page;
use utils;
use api;

function get(array $content)
{
    $resp = api\api\get_response_template();
    $resp['ok'] = true;
    $rendered = page\render\render_php($content['name']);
    $rendered = page\encode\minimize_html($rendered);
    $resp['content']['html'] = $rendered;
   // var_dump($resp);
    return $resp;
}

    function handle_request(array $request): array
    {
        switch ($request['action']) {
            case 'get':
                return get($request['content']);
                break;
                
            default:
                utils\utils\send_error(400, "Invalid action for page api");
                exit();
                break;
        }
    }
}
