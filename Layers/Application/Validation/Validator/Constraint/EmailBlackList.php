<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class EmailBlackList extends Constraint
{
    public $message = 'Les services de mails jetables ne sont pas autorisés.';

    public function validatedBy()
    {
        return 'email_black_list';
    }
}
