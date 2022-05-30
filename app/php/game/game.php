<?php
namespace game {
    use JsonSerializable;

    require_once('coords.php');
    require_once('ball.php');
    require_once('rect.php');
    use game;

    class GameState implements JsonSerializable
    {
        private int $target = 15;
        private array $decomp = [[5, 5, 5], [6, 5, 4], [6, 6, 3], [7, 4, 4], [7, 5, 3],
            [7, 6, 2], [7, 7, 1], [8, 4, 3], [8, 5, 2], [8, 6, 1], [8, 7], [9, 3, 3], [9, 4, 2], [9, 5, 1], [9, 6],
            [10, 3, 2], [10, 4, 1], [10, 5], [11, 2, 2], [11, 3, 1],
            [11, 4], [12, 2, 1], [12, 3], [13, 1, 1],
            [13, 2], [14, 1]];
        private ?game\Rect $container = null;
        private ?array $balls = null;
        private ?int $score = null;
        private ?float $end_time = null;

        public function __construct()
        {
            $this->balls = array();
            $this->score = 0;
        }
        private function randomize_balls(game\Coords $ball_size): void
        {
            $ball_count = 7;
            $ball_sums = array();
            while (sizeof($ball_sums) < $ball_count) {
                $ball_sums = array_merge($ball_sums, $this->decomp[array_rand($this->decomp)]);
            }
            shuffle($ball_sums);

            $this->balls = array();
            for ($i = 0; $i < $ball_count; ++$i) {
                $x = ($i / $ball_count) + 0.05;
                $y = mt_rand() / (mt_getrandmax() * 2.5);
                $coords = new game\Coords([$x, $y]);
                $s1 = mt_rand(($ball_sums[$i] < 10) ? 0 : ($ball_sums[$i] - 9), ($ball_sums[$i] < 10) ? $ball_sums[$i] : 9);
                $s2 = $ball_sums[$i] - $s1;
                /*  var_dump('Sum', $ball_sums[$i]);
                 var_dump('ss', $s1, $s2);*/
                $this->balls[] = new game\Ball($coords, $ball_size, [$s1, $s2]);
            }
        }
        public function restart(game\Rect $container, game\Coords $ball_size): void
        {
            $this->container = $container;
            $this->randomize_balls($ball_size);
            $this->score = 0;
            $this->end_time = microtime(true) + 20.0;
        }
        public function next_round(): void
        {
            $this->randomize_balls($this->balls[0]->get_size());
            $this->end_time += 10.0;
            ++$this->score;
        }
        public function check_win(): bool
        {
            return $this->target === $this->container_sum();
        }
        public function get_balls(): array
        {
            return $this->balls;
        }
        public function get_score(): int
        {
            return $this->score;
        }
        public function move_ball(int $id, game\Coords $coords): void
        {
            $this->balls[$id]->set_pos($coords);
        }
        public function container_sum(): int
        {
            $sum = 0;
            foreach ($this->balls as $ball) {
                if ($this->container->contains_ball($ball)) {
                    $sum += $ball->get_sum();
                }
            }
            return $sum;
        }
        public function check_time(): bool
        {
            return microtime(true) < $this->end_time;
        }
        public function get_end_time(): float
        {
            return $this->end_time;
        }
        public function jsonSerialize(): array
        {
            return [];
        }
    }
}
