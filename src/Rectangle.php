<?php


namespace DrawingPrototype;

final class Rectangle extends Shape
{
    public function __construct(Position $position, Style $style, public float $width, public float $height,)
    {
        parent::__construct($position, $style);
    }
}
