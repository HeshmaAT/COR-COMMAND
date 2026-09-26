<?php


namespace DrawingPrototype;

final class Square extends Shape
{
    public function __construct(Position $position, Style $style, public float $Sidelength)
    {
        parent::__construct($position, $style);
    }
}
