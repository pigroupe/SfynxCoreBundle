<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint;

use Sfynx\CoreBundle\Layers\Application\Validation\Validator\Constraint\Generalisation\AbstractDateConstraint;

/**
 * Class PastDate
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Validator\ConstraintValidator
 */
class PastDate extends AbstractDateConstraint
{
    /**
     * @var string
     */
    public $message = 'This date "%value%" is not before "%date%"';

    /**
     * Compare two date time each other and returns true if the date to validate is in the past compared to the date
     * time limit. It also takes into account the possibility of the equality between dates.
     *
     * @param \DateTime $dateToValidate
     * @param \DateTime $dateLimit
     * @return bool
     */
    public function compareDate(\DateTime $dateToValidate, \DateTime $dateLimit): bool
    {
        if (true === $this->equal) {
            return $dateToValidate <= $dateLimit;
        }
        return $dateToValidate < $dateLimit;
    }
}
