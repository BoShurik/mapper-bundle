<?php
/**
 * User: boshurik
 * Date: 2019-07-18
 * Time: 13:40
 */

namespace BoShurik\MapperBundle\Generator\Helper;

use Symfony\Component\Console\Question\Question;

class ClassQuestionFactory
{
    /**
     * @var ClassAutoCompleter
     */
    private $autoCompleter;

    public function __construct(ClassAutoCompleter $autoCompleter)
    {
        $this->autoCompleter = $autoCompleter;
    }

    public function create(string $question): Question
    {
        $result = new Question($question);
        if (method_exists($result, 'setAutocompleterCallback')) { // Support symfony/console < 4.3
            $result->setAutocompleterCallback($this->autoCompleter);
        }

        return $result;
    }
}