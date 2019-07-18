<?php

namespace BoShurik\MapperBundle\Tests\Generator\Fixtures;

use Symfony\Component\Validator\Constraints as Assert;

final class PostModel
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