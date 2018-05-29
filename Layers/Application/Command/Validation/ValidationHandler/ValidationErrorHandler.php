<?php
namespace Sfynx\CoreBundle\Layers\Application\Command\Validation\ValidationHandler;

/**
 * Class ValidationErrorHandler
 *
 * @category Sfynx\CoreBundle\Layer
 * @package Application
 * @subpackage Validation\Generalisation\ValidationHandler
 */
class ValidationErrorHandler
{
    /**
     * @param array $errors output of validator->validateValue() method
     * @return array
     */
    public static function arrayAll(\IteratorAggregate $errors)
    {
        $tab = [];
        foreach ($errors as $error) {
            $field = substr($error->getPropertyPath(), 1, -1);
            $tab[$field] = $error->getMessage();
        }
        return $tab;
    }
}
