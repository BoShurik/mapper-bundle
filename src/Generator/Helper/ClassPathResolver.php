<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 12:41
 */

namespace BoShurik\MapperBundle\Generator\Helper;

class ClassPathResolver
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var array
     */
    private $classMap;

    public function __construct(string $projectDir, array $classMap)
    {
        $this->projectDir = rtrim($projectDir, \DIRECTORY_SEPARATOR);
        $this->classMap = $classMap;
    }

    public function resolve(string $class): string
    {
        foreach ($this->classMap as $namespace => $namespaceRoot) {
            if (strpos($class, $namespace) !== 0) {
                continue;
            }

            $path = str_replace('\\', \DIRECTORY_SEPARATOR, str_replace($namespace, '', $class));

            return $this->projectDir . \DIRECTORY_SEPARATOR . $namespaceRoot . $path . '.php';
        }

        throw new \RuntimeException(sprintf('Cant resolve path for class "%s"', $class));
    }
}