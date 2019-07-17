<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 12:57
 */

namespace BoShurik\MapperBundle\Generator\Helper;

class ClassPathResolverFactory
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

    public function create(): ClassPathResolver
    {
        return new ClassPathResolver($this->projectDir, $this->classMapResolver->resolve());
    }
}