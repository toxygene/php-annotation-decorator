<?php
namespace Toxygene\PhpAnnotationDecorator;

use AppendIterator;
use Doctrine\Common\Annotations\Reader;
use hanneskod\classtools\Iterator\ClassIterator;
use IteratorIterator;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Toxygene\PhpAnnotationDecorator\Annotation\Decorator;

class MappingDriver
{
    /**
     * @var DecorationDriver
     */
    private $decorationDriver;

    /**
     * @var array
     */
    private $paths;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param DecorationDriver $decorationDriver
     * @param Reader $reader
     * @param array $paths
     */
    public function __construct(DecorationDriver $decorationDriver, Reader $reader, $paths)
    {
        $this->decorationDriver = $decorationDriver;
        $this->paths            = $paths;
        $this->reader           = $reader;
    }

    /**
     *
     */
    public function decorate()
    {
        foreach ($this->getClassMap() as $className => $refClass) {
            foreach ($this->reader->getClassAnnotations($refClass) as $classAnnotation) {
                if ($classAnnotation instanceof Decorator) {
                    foreach ($refClass->getMethods() as $refMethod) {
                        $this->decorationDriver->decorateMethod($refClass->getName(), $refMethod->getName(), $classAnnotation);
                    }
                }
            }

            foreach ($refClass->getMethods() as $refMethod) {
                $className  = $refClass->getName();
                $methodName = $refMethod->getName();

                foreach ($this->reader->getMethodAnnotations($refMethod) as $methodAnnotation) {
                    if ($methodAnnotation instanceof Decorator) {
                        $methodName = $this->decorationDriver->decorateMethod($className, $methodName, $methodAnnotation);
                    }
                }
            }
        }
    }

    /**
     * @return ReflectionClass[]
     */
    public function getClassMap()
    {
        $iterator = new AppendIterator();
        foreach ($this->paths as $path) {
            $iterator->append(
                new IteratorIterator(
                    new ClassIterator(
                        (new Finder())->in($path)
                    )
                )
            );
        }
        return $iterator;
    }
}
