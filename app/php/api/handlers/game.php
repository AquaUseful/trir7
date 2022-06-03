<?php

namespace api\game {
    require_once('../utils/session.php');
    require_once('../utils/utils.php');
    require_once('../game/game.php');
    require_once('../game/rect.php');
    require_once('../game/coords.php');
    require_once('api.php');
    use utils;
    use api;
    use game;

    function restart(array $content): array
    {
        // var_dump($content);
        $pos = new game\Coords($content['container']['pos']);
        $size = new game\Coords($content['container']['size']);
        //var_dump($npos);
        $container = new game\Rect($pos, $size);
        //var_dump($container);
        $ball_size = new game\Coords($content['ball_size']);
        $_SESSION['game']->restart($container, $ball_size);
        //var_dump($_SESSION['game']);
        return api\api\construct_response(true, '', []);
    }

    function move(array $content): array
    {
        $_SESSION['game']->move_ball($content['id'], new game\Coords($content['pos']));
        $win = $_SESSION['game']->check_win();
        if ($win) {
            $_SESSION['game']->next_round();
        }
        return api\api\construct_response(true, '', ['win' => $win]);
    }
    function getballs(array $content): array
    {
        $balls = $_SESSION['game']->get_balls();
        return api\api\construct_response(true, '', $balls);
    }
    function getscore(array $content): array
    {
        return api\api\construct_response(true, '', ['score' => $_SESSION['game']->get_score()]);
    }

    function gettime(array $content): array
    {
        $time_left = $_SESSION['game']->get_end_time() - microtime(true);
        return api\api\construct_response(true, '', ['time' => $time_left]);
    }
    function update(array $content): array
    {
        $time_left = $_SESSION['game']->get_end_time() - microtime(true);
        $_SESSION['game']->update();
        return api\api\construct_response(true, '', ['time' => $time_left]);
    }

    function getlives(array $content): array
    {
        $lives = $_SESSION['game']->get_lives();
        return api\api\construct_response(true, '', ['lives' => $lives]);
    }

    function handle_request(array $request): array
    {
        // utils\session\filter_logged_in();
        switch ($request['action']) {
            case 'restart':
                return restart($request['content']);
            case 'move':
                return move($request['content']);
            case 'getballs':
                return getballs($request['content']);
            case 'getscore':
                return getscore($request['content']);
            case 'gettime':
                return gettime($request['content']);
            case 'update':
                return update($request['content']);
            case 'getlives':
                return getlives($request['content']);
            default:
                utils\utils\send_error(400, "Invalid action for game api");
                exit();
        }
    }

}
