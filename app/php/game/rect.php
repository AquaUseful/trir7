<?php
namespace game {
    use JsonSerializable;

    require_once('coords.php');
    require_once('ball.php');
    use game;

    class Rect implements JsonSerializable
    {
        private ?game\Coords $pos = null;
        private ?game\Coords $size = null;

        public function __construct(game\Coords $pos, game\Coords $size)
        {

            $this->pos = $pos;
            $this->size = $size;
        }
        public function contains_ball(game\Ball $ball): bool
        {
            $left = $ball->get_pos()->get_x() >= $this->pos->get_x();
            $top = $ball->get_pos()->get_y() >= $this->pos->get_y();
            $right = ($ball->get_pos()->get_x() + $ball->get_size()->get_x()) <= ($this->pos->get_x() + $this->size->get_x());
            $bottom = ($ball->get_pos()->get_y() + $ball->get_size()->get_y()) <= ($this->pos->get_y() + $this->size->get_y());
            return $left && $top && $right && $bottom;
        }

        public function jsonSerialize(): array
        {
            return ['pos' => $this->pos->jsonSerialize(), 'size' => $this->size->jsonSerialize()];
        }
    }
}
