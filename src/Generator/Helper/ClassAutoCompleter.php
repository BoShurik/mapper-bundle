<?php
/**
 * User: boshurik
 * Date: 2019-07-17
 * Time: 21:14
 */

namespace BoShurik\MapperBundle\Generator\Helper;

class ClassAutoCompleter
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
        $this->projectDir = $projectDir;
        $this->classMap = $classMap;
    }

    public function __invoke(string $input): array
    {
        $input = preg_replace('%(\\\\|^)[^\\\\]*$%', '$1', $input);

        $path = null;
        foreach ($this->classMap as $namespace => $namespaceRoot) {
            if (strpos($input, $namespace) !== 0) {
                continue;
            }

            $path = str_replace('\\', \DIRECTORY_SEPARATOR, str_replace($namespace, '', $input));
            $path = preg_replace('%(/|^)[^/]*$%', '$1', $path);

            $path = $this->projectDir . \DIRECTORY_SEPARATOR . $namespaceRoot . $path;
        }
        if (!$path) {
            return array_keys($this->classMap);
        }

        $foundFilesAndDirs = @scandir($path) ?: [];
        $foundFilesAndDirs = array_values(array_filter($foundFilesAndDirs, function($value) {
            return $value !== '.' && $value !== '..';
        }));

        return array_map(function ($dirOrFile) use ($input, $path) {
            if (is_dir($path . $dirOrFile)) {
                return $input.$dirOrFile.'\\';
            }

            return $input.pathinfo($dirOrFile, PATHINFO_FILENAME);
        }, $foundFilesAndDirs);
    }
}