<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\TraitRegex;
use Symfony\Component\Validator\Constraint;

/**
 * Class Title
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class Title extends Constraint
{
    use TraitRegex;

    /**
     * @var string
     */
    public $message = 'The string %string% contains an illegal character: it can only contain words.';

    /**
     * {@inheritdoc}
     */
    public function __construct($options = [])
    {
        parent::__construct(array_merge(['regex' => '/^[a-zA-Z0-9_\- ]+$/u'], $options));
    }
}
