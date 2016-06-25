<?php
namespace Toxygene\PhpAnnotationDecorator\Annotation;

/**
* @Annotation
* @Target({"CLASS","METHOD"})
*/
class Decorator
{
    /**
     * @var array
     */
    public $options = [];

    /**
     * @var string
     */
    public $name;

    /**
     *
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     *
     */
    public function getOptions()
    {
        return $this->options;
    }
}
