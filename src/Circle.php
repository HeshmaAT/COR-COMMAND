<?php


namespace DrawingPrototype;

final class Circle extends Shape
{
    public function __construct(Position $position, Style $style, public float $radius)
    {
        parent::__construct($position, $style);
    }
}
