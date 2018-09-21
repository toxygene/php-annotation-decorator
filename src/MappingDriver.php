<?php
namespace Toxygene\PhpAnnotationDecorator;

use AppendIterator;
use Doctrine\Common\Annotations\DocParser;
use Doctrine\Common\Annotations\Reader;
use IteratorIterator;
use PhpParser\Error;
use PhpParser\Node;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Toxygene\PhpAnnotationDecorator\Annotation\Decorator;

class MappingDriver
{
   /**
     * @var array
     */
    private $paths;

    /**
     * @var Reader
     */
    private $reader;

    /**
     * @param Reader $reader
     * @param array $paths
     */
    public function __construct(Reader $reader, $paths)
    {
        $this->paths = $paths;
        $this->reader = $reader;
    }

    /**
     * Decorate the PHP files
     */
    public function decorate()
    {
        foreach ($this->getPhpFiles() as $file) {
            $code = file_get_contents($file);

            $parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);
            try {
                $statements = $parser->parse($code);
            } catch (Error $error) {
                echo "Parse error: {$error->getMessage()}\n";
                return;
            }

            $this->decorateStatements($statements);

            $prettyPrinter = new Standard();
            echo $prettyPrinter->prettyPrintFile($statements);
        }
    }

    /**
     * @return AppendIterator|ReflectionClass[]
     */
    public function getPhpFiles()
    {
        $iterator = new AppendIterator();
        foreach ($this->paths as $path) {
            $iterator->append(
                new IteratorIterator(
                    (new Finder())
                        ->in($path)
                        ->name('*.php')
                )
            );
        }
        return $iterator;
    }
}
