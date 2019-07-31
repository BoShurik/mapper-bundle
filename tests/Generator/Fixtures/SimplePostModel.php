<?php
/**
 * User: boshurik
 * Date: 2019-07-31
 * Time: 15:00
 */

namespace BoShurik\MapperBundle\Tests\Generator\Fixtures;

final class SimplePostModel
{
    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string|null
     *
     * @Assert\NotBlank()
     */
    public $name;
}