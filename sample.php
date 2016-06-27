<?php
require 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\CachedReader;
use Doctrine\Common\Cache\ArrayCache;
use Phramz\Doctrine\Annotation\Scanner\Scanner;
use Toxygene\PhpAnnotationDecorator\DecorationDriver;
use Toxygene\PhpAnnotationDecorator\MappingDriver;
use Toxygene\PhpAnnotationDecorator\Registry;
use Toxygene\PhpAnnotationDecorator\Sample\Decorator\AppendNewlineDecorator;
use Toxygene\PhpAnnotationDecorator\Sample\Decorator\FlipDecorator;
use Toxygene\PhpAnnotationDecorator\Sample\Decorator\Rot13Decorator;
use Toxygene\PhpAnnotationDecorator\Sample\Foo;

// Register the decorators
$registry = new Registry();
$registry->add('flip', new FlipDecorator());
$registry->add('newline', new AppendNewlineDecorator());
$registry->add('rot13', new Rot13Decorator());

// Register the Decorator annotation
AnnotationRegistry::registerFile('./src/Annotation/Decorator.php');

// Create the driver
$mappingDriver = new MappingDriver(
    new DecorationDriver(
        $registry
    ),
    new CachedReader(new AnnotationReader(), new ArrayCache()),
    ['./sample']
);

$mappingDriver->decorate();

// Test the decorators
$test = new Foo();
echo $test->bar('a', 'b');
