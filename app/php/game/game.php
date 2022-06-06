<?php
namespace game {
    use JsonSerializable;

    require_once('coords.php');
    require_once('ball.php');
    require_once('rect.php');
    require_once('timer.php');
    require_once('ball_generator.php');
    require_once('../db/db.php');
    use game;
    use db;

    class GameState
    {
        private int $initial_lives = 3;
        private int $ball_count = 7;
        private ?int $target = null;
        private ?array $decomp = null;
        private ?game\Rect $container = null;
        private ?array $balls = null;
        private ?int $score = null;
        private ?int $lives = null;
        private ?game\Timer $timer = null;
        private ?game\BallGenerator $ball_gen = null;

        public function __construct()
        {
            $this->score = 0;
            $this->lives = $this->initial_lives;
            $this->timer = new game\Timer();
            $this->ball_gen = new game\BallGenerator();
        }
        private function randomize_balls(game\Coords $ball_size): void
        {
            $this->ball_gen->update_balls($ball_size);
        }
        public function restart(game\Rect $container, game\Coords $ball_size): void
        {
            $this->container = $container;
            $this->score = 0;
            $this->timer->reset_perion();
            $this->timer->update_end_time();
            $this->lives = $this->initial_lives;

            $this->ball_gen->reset_max_balls();
            $this->randomize_balls($ball_size);
        }
        public function next_round(): void
        {
            if (mt_rand(0, 1) === 0) {
                $this->ball_gen->inc_max_balls();
            }
            $this->randomize_balls($this->ball_gen->get_balls()[0]->get_size());
            $this->timer->dec_time();
            $this->timer->update_end_time();
            ++$this->score;
            $user = db\get_record('user', $_SESSION['login']);
            if (($user['record'] === null) || ($this->score > $user['record'])) {
                $user['record'] = $this->score;
                db\set_record('user', $user);
            }
        }
        public function update(): void
        {
            if ($this->timer->is_time_out()) {
                --$this->lives;
                $this->timer->reset_perion();
                $this->timer->update_end_time();
                $this->randomize_balls($this->ball_gen->get_balls()[0]->get_size());
            }
        }
        public function check_win(): bool
        {
            return ($this->ball_gen->get_target() === $this->container_sum()) && (!$this->timer->is_time_out());
        }
        public function get_balls(): array
        {
            return $this->ball_gen->get_balls();
        }
        public function get_score(): int
        {
            return $this->score;
        }
        public function move_ball(int $id, game\Coords $coords): void
        {
            $this->ball_gen->move_ball($id, $coords);
        }
        public function container_sum(): int
        {
            $sum = 0;
            foreach ($this->ball_gen->get_balls() as $ball) {
                if ($this->container->contains_ball($ball)) {
                    $sum += $ball->get_sum();
                }
            }
            return $sum;
        }
        public function check_time(): bool
        {
            return $this->timer->is_time_out();
        }
        public function get_end_time(): float
        {
            return $this->timer->get_end_time();
        }
        public function get_lives(): int
        {
            return $this->lives;
        }
        public function get_target(): int
        {
            return $this->ball_gen->get_target();
        }
    }
}
