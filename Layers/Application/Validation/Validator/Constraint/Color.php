<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\TraitRegex;
use Symfony\Component\Validator\Constraint;

/**
 * Class Color
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class Color extends Constraint
{
    use TraitRegex;

    /**
     * @var string
     */
    public $message = 'Invalid color code "%string%"';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = [])
    {
        parent::__construct(array_merge(['regex' => '/^#([a-f0-9]{3}){1,2}$/i'], $options));
    }
}
