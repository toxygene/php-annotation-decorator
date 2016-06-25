<?php
namespace Toxygene\PhpAnnotationDecorator;

class Registry
{
    private $callbacks = [];
    public function add($name, $callback)
    {
        $this->callbacks[$name] = $callback;
        return $this;
    }
    public function get($name)
    {
        return $this->callbacks[$name];
    }
}
