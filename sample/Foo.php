<?php
namespace Toxygene\PhpAnnotationDecorator\Sample;

use Toxygene\PhpAnnotationDecorator\Annotation\Decorator;

/**
 * @Decorator(name="newline")
 */
class Foo
{
    /**
     * @Decorator(name="rot13")
     * @Decorator(name="flip")
     */
    public function bar($a, $b)
    {
        return "{$a}, {$b}";
    }

    public function baz()
    {
        return "asdf";
    }
}
