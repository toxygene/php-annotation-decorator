<?php
namespace Toxygene\PhpAnnotationDecorator\Sample\Decorator;

class AppendNewlineDecorator
{
    public function __invoke($method, $options, $a, $b)
    {
        return $method($a, $b) . "\n";
    }
}
