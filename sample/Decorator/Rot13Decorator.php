<?php
namespace Toxygene\PhpAnnotationDecorator\Sample\Decorator;

class Rot13Decorator
{
    public function __invoke($method, $options, $a, $b)
    {
        return str_rot13($method($a, $b));
    }
}
