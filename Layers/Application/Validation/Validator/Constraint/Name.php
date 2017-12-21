<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\TraitRegex;
use Symfony\Component\Validator\Constraint;

/**
 * Class Name
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class Name extends Constraint
{
    use TraitRegex;

    /**
     * @var string
     */
    public $message = 'The string "%string%" contains an illegal character: it can only contain letters.';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = [])
    {
        parent::__construct(array_merge(['regex' => '/^[[:alpha:]]+([\-\' ][[:alpha:]]+)*$/u'], $options));
    }
}
