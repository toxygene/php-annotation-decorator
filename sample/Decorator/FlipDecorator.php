<?php
namespace Toxygene\PhpAnnotationDecorator\Sample\Decorator;

class FlipDecorator
{
    public function __invoke($method, $options, $a, $b)
    {
        return strrev($method($a, $b));
    }
}
