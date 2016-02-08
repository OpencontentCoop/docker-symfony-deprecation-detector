<?php

namespace SensioLabs\DeprecationDetector\Finder;

use SensioLabs\DeprecationDetector\Console\Output\VerboseProgressOutput;
use SensioLabs\DeprecationDetector\FileInfo\PhpFileInfo;
use SensioLabs\DeprecationDetector\Parser\ParserInterface;
use Symfony\Component\Finder\Finder;

class ParsedPhpFileFinder extends Finder
{
    /**
     * @var ParserInterface
     */
    protected $parser;

    /**
     * @var VerboseProgressOutput
     */
    protected $progressOutput;

    /**
     * @var \PhpParser\Error[]
     */
    protected $parserErrors = array();

    /**
     * @param ParserInterface       $parser
     * @param VerboseProgressOutput $progressOutput
     */
    public function __construct(ParserInterface $parser, VerboseProgressOutput $progressOutput)
    {
        parent::__construct();

        $this->parser = $parser;
        $this->files()->name('*.php');

        $this->progressOutput = $progressOutput;
    }

    /**
     * @return \Iterator
     */
    public function getIterator()
    {
        $iterator = parent::getIterator();
        $files = new \ArrayIterator();
        $total = $this->count();

        $this->progressOutput->start($total);

        $i = 0;
        foreach ($iterator as $file) {
            $file = PhpFileInfo::create($file);

            try {
                $this->progressOutput->advance(++$i, $file);
                $this->parser->parseFile($file);
            } catch (\PhpParser\Error $ex) {
                $raw = $ex->getRawMessage().' in file '.$file;
                $ex->setRawMessage($raw);
                $this->parserErrors[] = $ex;
            }

            $files->append($file);
        }

        $this->progressOutput->end();

        return $files;
    }

    /**
     * @return int
     */
    public function count()
    {
        return iterator_count(parent::getIterator());
    }

    /**
     * @return \PhpParser\Error[]
     */
    public function getParserErrors()
    {
        return $this->parserErrors;
    }

    /**
     * @param ParserInterface $parser
     * @param VerboseProgressOutput $progressOutput
     * @return ParsedPhpFileFinder
     */
    public static function deprecationFinder(ParserInterface $parser, VerboseProgressOutput $progressOutput)
    {
        $finder = new ParsedPhpFileFinder($parser, $progressOutput);
        $finder
            ->contains('@deprecated')
            ->exclude('vendor')
            ->exclude('Tests')
            ->exclude('Test');

        return $finder;

    }

    /**
     * @param ParserInterface $parser
     * @param VerboseProgressOutput $progressOutput
     * @return ParsedPhpFileFinder
     */
    public static function usageFinder(ParserInterface $parser, VerboseProgressOutput $progressOutput)
    {
        $finder = new ParsedPhpFileFinder($parser, $progressOutput);
        $finder
            ->exclude('vendor')
            ->exclude('Tests')
            ->exclude('Test');

        return $finder;
    }

    /**
     * @return ParserInterface
     */
    public function getParser()
    {
        return $this->parser;
    }

    /**
     * @return VerboseProgressOutput
     */
    public function getOutput()
    {
        return $this->progressOutput;
    }
}
