<?php
namespace api\session {
    require_once('api.php');
    require_once('../utils/utils.php');
    require_once('../utils/session.php');
    require_once('../db/db.php');

    use api;
    use utils;
    use db;

    function logout(array $content): array
    {
        utils\session\deinit_session();
        return api\api\construct_response(true, '', []);
    }

    function login(array $content): array
    {
        if (db\has_record_with_key('user', $content['login'])) {
            $user = db\get_record('user', $content['login']);
            if ($user['password'] === $content['password']) {
                $_SESSION['login'] = $user['login'];
                $_SESSION['loggedIn'] = true;
                return api\api\construct_response(true, '', []);
            }
            else {
                return api\api\construct_response(false, 'password', ['displayError' => 'Неверный пароль']);
            }
        }
        else {
            return api\api\construct_response(false, 'login', ['displayError' => 'Несуществующий логин']);
        }
    }

    function getlogin(array $content): array
    {
        if ($_SESSION['loggedIn']) {
            return api\api\construct_response(true, '', ['login' => $_SESSION['login']]);
        }
        else {
            return api\api\construct_response(false, 'nologin', []);
        }
    }

    function register(array $content): array {
        if (!db\has_record_with_key('user', $content['login'])) {
            $newUser = db\create_empty_record('user');
            foreach (array_keys($newUser) as $fieldName) {
                $newUser[$fieldName] = $content[$fieldName];
            }
            db\set_record('user', $newUser);
           return api\api\construct_response(true, '', []);
        }
        else {
            $resp['success'] = false;
            return api\api\construct_response(false, 'exists', ['displayError' => 'Уже зарегистрирован']);
        }
    }

    function handle_request(array $request): array
    {
        switch ($request['action']) {
            case 'login':
                return login($request['content']);

            case 'logout':
                return logout($request['content']);

            case 'getlogin':
                return getlogin($request['content']);

            case 'register';
                return register($request['content']);

            default:
                utils\utils\send_error(400, "Invalid action for page api");
                exit();
        }
    }
}