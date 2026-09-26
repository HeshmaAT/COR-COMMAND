<?php

use DrawingPrototype\Circle;
use DrawingPrototype\Position;
use DrawingPrototype\Rectangle;
use DrawingPrototype\Square;
use DrawingPrototype\Style;

require_once __DIR__ . '/bootstrap.php';

$circle = new Circle(new Position(10, 20), new Style('red', 2), 15);
$circle->style->color = 'blue';
$circleCopy = $circle->duplicate();
$circleCopy->position->x = 100;
$circleCopy->style->color = 'green';

echo "Circle original: color={$circle->style->color}, x={$circle->position->x}\n";
echo "Circle clone: color={$circleCopy->style->color}, x={$circleCopy->position->x}\n\n";

$rectangle = new Rectangle(new Position(5, 8), new Style('black', 1), 40, 20);
$rectangle->style->borderWidth = 3;
$rectangleCopy = $rectangle->duplicate();
$rectangleCopy->width = 80;

echo "Rectangle original: width={$rectangle->width}\n";
echo "Rectangle clone: width={$rectangleCopy->width}\n\n";

$Square = new Square(new Position(20, 20), new Style('Matcha', 10),25);
$rectangle->style->borderWidth = 30;
$SquareCopy = $Square->duplicate();
$SquareCopy->style->color = 'ENG/MM';

echo "Square original: Color={$Square->style->color}\n";
echo "SquareCopy clone: Color={$SquareCopy->style->color}\n";
