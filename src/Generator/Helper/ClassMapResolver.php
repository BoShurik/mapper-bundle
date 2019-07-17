<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 15:08
 */

namespace BoShurik\MapperBundle\Generator\Helper;

class ClassMapResolver
{
    /**
     * @var string
     */
    private $projectDir;

    /**
     * @var array
     */
    private $classMap;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
        $this->classMap = null;
    }

    public function resolve(): array
    {
        if (is_array($this->classMap)) {
            return $this->classMap;
        }

        $composerJson = json_decode(file_get_contents(sprintf('%s/composer.json', $this->projectDir)), true);

        $this->classMap = [];
        $this->classMap = array_merge($this->classMap, $composerJson['autoload']['psr-4'] ?? []);
        $this->classMap = array_merge($this->classMap, $composerJson['autoload-dev']['psr-4'] ?? []);

        return $this->classMap;
    }
}