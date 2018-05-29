<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EmailBlackListValidator
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class EmailBlackListValidator extends ConstraintValidator
{
    /** @var array */
    private $blackList;

    /**
     * @param array $blackList
     */
    public function setBlackList(array $blackList)
    {
        $this->blackList = $blackList;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $domainArray = preg_split("/@/", $value);
        if (count($domainArray) > 1) {
            $domain = $domainArray[1];
            if (!(null === $this->blackList)
                && in_array($domain, $this->blackList)
            ) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
