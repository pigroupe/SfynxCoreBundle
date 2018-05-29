<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class PhoneNumber
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class PhoneNumber extends Constraint
{
    protected $message = 'The string "%string%" contains an illegal character: it can only contain numbers.';

    protected $regex = '/^[0-9]{10}$/i'; //can be better !

    public function validatedBy()
    {
        return get_class($this).'Validator';
    }

    /**
     * @return string
     */
    public function getRegex()
    {
        return $this->regex;
    }

    /**
     * @param string $regex
     */
    public function setRegex($regex)
    {
        $this->regex = $regex;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
