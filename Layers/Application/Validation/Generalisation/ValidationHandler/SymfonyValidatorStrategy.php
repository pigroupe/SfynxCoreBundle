<?php
namespace Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\ValidationHandler;

use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator\ValidatorInterface as LegacyValidatorInterface;
use Sfynx\CoreBundle\Layers\Application\Validation\Generalisation\Interfaces\ValidatorInterface;

/**
 * Class SymfonyValidatorStrategy
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\ValidationHandler
 */
class SymfonyValidatorStrategy implements ValidatorInterface
{
    /** @var  LegacyValidatorInterface */
    protected $validator;

    /**
     * SymfonyValidatorStrategy constructor.
     * @param LegacyValidatorInterface $validator
     */
    public function __construct(LegacyValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $data
     * @param array $constraints must be like [field_name => constraints, field_name => constraints, field_name => constraints]
     */
    public function validate($data, array $constraints)
    {
        return $this->validator->validate($data, new Collection($constraints));
    }
}
