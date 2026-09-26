<?php


namespace DrawingPrototype;

abstract class Shape
{
    public function __construct(public Position $position, public Style $style)
    {
    }

    public function duplicate(): static
    {
        return clone $this;
    }

    public function __clone(): void
    {
        $this->position = clone $this->position;
        $this->style = clone $this->style;
    }

}
