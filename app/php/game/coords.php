<?php
namespace game {
    use JsonSerializable;

    class Coords implements JsonSerializable
    {
        private ?float $x = null;
        private ?float $y = null;

        public function __construct(array $coords)
        {
            $this->x = $coords[0];
            $this->y = $coords[1];
        }
        public function set(float $x, float $y): void
        {
            $this->x = $x;
            $this->y = $y;
        }
        public function get_x(): float
        {
            return $this->x;
        }
        public function get_y(): float
        {
            return $this->y;
        }
        public function set_x(float $x): void
        {
            $this->x = $x;
        }
        public function set_y(float $y): void
        {
            $this->y = $y;
        }
        public function from_array(array $arr): void
        {
            $this->x = $arr[0];
            $this->y = $arr[1];
        }
        public function jsonSerialize(): array
        {
            return [$this->x, $this->y];
        }
    }
}
