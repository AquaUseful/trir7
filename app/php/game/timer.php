<?php
namespace game {
    class Timer
    {
        private int $max_period = 30;
        private int $min_period = 8;
        private int $period_decrement = 2;
        private ?int $current_period = null;

        private ?float $end_time = null;

        public function __construct()
        {
            $this->reset_perion();
            $this->update_end_time();
        }
        public function update_end_time(): void
        {
            $this->end_time = microtime(true) + $this->current_period;
        }
        public function reset_perion(): void
        {
            $this->current_period = $this->max_period;
        }

        public function is_time_out(): bool
        {
            return (microtime(true) >= $this->end_time);
        }

        public function dec_time(): void
        {
            if (($this->current_period - $this->period_decrement) >= $this->min_period) {
                $this->current_period -= $this->period_decrement;
            }
        }

        public function get_end_time(): float
        {
            return $this->end_time;
        }

    }
}