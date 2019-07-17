<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 15:07
 */

namespace BoShurik\MapperBundle\Generator\Helper;

class ClassAutoCompleterFactory
{
    /**
     * @var ClassMapResolver
     */
    private $classMapResolver;

    /**
     * @var string
     */
    private $projectDir;

    public function __construct(ClassMapResolver $classMapResolver, string $projectDir)
    {
        $this->classMapResolver = $classMapResolver;
        $this->projectDir = $projectDir;
    }

    public function create(): ClassAutoCompleter
    {
        return new ClassAutoCompleter($this->projectDir, $this->classMapResolver->resolve());
    }
}