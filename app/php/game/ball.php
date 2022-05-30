<?php
namespace game {
    use JsonSerializable;

    require_once('coords.php');
    use game;

    class Ball implements JsonSerializable
    {
        private ?game\Coords $pos = null;
        private ?game\Coords $size = null;
        private ?array $val = null;

        public function __construct(game\Coords $pos, game\Coords $size, array $val)
        {
            $this->pos = $pos;
            $this->size = $size;
            $this->val = $val;
        }
        public function get_pos(): game\Coords
        {
            return $this->pos;
        }
        public function set_pos(game\Coords $pos): void
        {
            $this->pos = $pos;
        }

        public function get_size(): game\Coords
        {
            return $this->size;
        }
        public function set_size(game\Coords $size): void
        {
            $this->size = $size;
        }
        public function get_sum(): int
        {
            return array_sum($this->val);
        }
        public function jsonSerialize(): array
        {
            return ['pos' => $this->pos->jsonSerialize(), 'val' => $this->val];
        }
    }
}
