<?php
namespace game {
    require_once('ball.php');
    require_once('../utils/utils.php');
    use game;
    use utils;

    class BallGenerator
    {
        private int $total_balls = 7;
        private int $min_balls = 2;
        private int $max_balls_min = 2;
        private int $max_balls_max = 5;
        private int $min_target = 10;
        private ?int $target = null;
        private ?int $max_balls = null;
        private ?array $balls = null;
        public function __construct()
        {
            $this->ball_sums = array();
            $this->target = 0;
            $this->reset_max_balls();
            $this->balls = array();
        }
        public function inc_max_balls(): void
        {
            if (($this->max_balls + 1) <= $this->max_balls_max) {
                ++$this->max_balls;
            }
        }
        public function reset_max_balls(): void
        {
            $this->max_balls = $this->max_balls_min;
        }
        public function update_balls(game\Coords $ball_size): void
        {
            $ball_sums = $this->generate_sums();
            while (sizeof($ball_sums) < $this->total_balls) {
                $ball_sums[] = mt_rand(1, 17);
            }
            shuffle($ball_sums);
            $this->balls = [];
            $i = 0;
            foreach ($ball_sums as $ball_sum) {
                $x = ($i / $this->total_balls) + $ball_size->get_y() * 0.1;
                $y = mt_rand() / (mt_getrandmax() * 2.5);
                $coords = new game\Coords([$x, $y]);

                $s1 = mt_rand(($ball_sum < 10) ? 0 : ($ball_sum - 9), ($ball_sum < 10) ? $ball_sum : 9);
                $s2 = $ball_sum - $s1;

                $this->balls[] = new game\Ball($coords, $ball_size, [$s1, $s2]);
                ++$i;
            }
        }
        private function generate_sums(): array
        {
            $remain_balls = mt_rand($this->min_balls, $this->max_balls);
            $this->target = mt_rand($this->min_target, $remain_balls * 18);
            $ball_sums = array();
            $remain = $this->target;
            while ($remain > 0) {
                $min_ball_sum = $remain - (--$remain_balls * 18);
                $min_ball_sum = ($min_ball_sum < 1) ? 1 : $min_ball_sum;
                $max_ball_sum = ($remain > 18) ? 18 : $remain;
                $ball_sum = mt_rand($min_ball_sum, $max_ball_sum);
                $remain -= $ball_sum;
                $ball_sums[] = $ball_sum;
            }
            return $ball_sums;
        }
        public function get_balls(): array
        {
            return $this->balls;
        }
        public function get_target(): int
        {
            return $this->target;
        }
        public function move_ball(int $id, game\Coords $coords): void
        {
            $this->balls[$id]->set_pos($coords);
        }
    }
}