<?php
namespace Toxygene\PhpAnnotationDecorator;

use Doctrine\Common\Annotations\Reader;
use Toxygene\PhpAnnotationDecorator\Annotation\Decorator;

class AnnotationDriver
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * Constructor
     *
     * @param Reader $reader
     * @param Registry $registry
     */
    public function __construct(Reader $reader, Registry $registry)
    {
        $this->reader   = $reader;
        $this->registry = $registry;
    }

    /**
     * Decorate a method
     *
     * @param string $className
     * @param string $methodName
     * @param Decorator $decorator
     * @return string
     */
    public function decorateMethod($className, $methodName, Decorator $decorator)
    {
        $callback = $this->registry->get($decorator->getName());

        $newMethodName = $decorator->getName() . '_decorated_' . $methodName;

        // Decorator method
        $decorator = function() use ($callback, $newMethodName, $decorator) {
            // Create a Closure of the original method
            $original = function() use ($newMethodName) {
                return call_user_func_array([$this, $newMethodName], func_get_args());
            };

            // Call the decorator and pass the original method, annotation options, and method arguments to it
            return call_user_func_array(
                $callback,
                array_merge(
                    [$original],
                    [$decorator->getOptions()],
                    func_get_args()
                )
            );
        };

        // Rename the original method
        runkit_method_rename(
            $className,
            $methodName,
            $newMethodName
        );

        // Add the decorator in place of the original method
        runkit_method_add(
            $className,
            $methodName,
            $decorator
        );

        return $newMethodName;
    }
}
