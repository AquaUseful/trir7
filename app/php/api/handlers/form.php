<?php

namespace api\form {
    require_once('api.php');
    require_once('../utils/validate.php');

    use api;
    use utils;

    function validate(array $content): array
    {
        $result = utils\validate\validate_form($content);
        return api\api\construct_response(true, '', $result);
    }

    function getinfo(array $content): array
    {
        $info = utils\validate\get_forminfo($content['name']);
        return api\api\construct_response(true, '', $info);
    }

    function handle_request(array $request): array
    {
        switch ($request['action']) {
            case 'validate':
                return validate($request['content']);

            case 'getinfo':
                return getinfo($request['content']);

            default:
                utils\utils\send_error(400, "Invalid action for page api");
                exit();
        }
    }
}