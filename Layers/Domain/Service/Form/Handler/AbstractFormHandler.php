<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Form\Handler;

use Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces\FormHandlerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Form\Generalisation\Interfaces\FormInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Manager\Generalisation\Interfaces\ManagerInterface;
use Sfynx\CoreBundle\Layers\Domain\Service\Request\Generalisation\RequestInterface;

/**
 * A FormHandler is a object that is reponsable of form binding and post treatment
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Form\Handler
 * @author     Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since      2015-02-16
 */
abstract class AbstractFormHandler implements FormHandlerInterface
{
    /** @var FormInterface */
    protected $form;
    /** @var RequestInterface */
    protected $request;
    /** @var ManagerInterface */
    protected $manager;
    /** @var mixed */
    protected $modelData;

    /**
     * AbstractFormHandler constructor.
     * @param FormInterface $form
     * @param Request $request
     */
    public function __construct(FormInterface $form, RequestInterface $request)
    {
        $this->form = $form;
        $this->request = $request;
    }

    /**
     * @return array List of accepted request methods ['POST', 'GET', ...]
     */
    abstract protected function getValidMethods(): array;

    /**
     * This method implements the pre-process
     * @return void
     */
    abstract protected function preProcess(): void;

    /**
     * This method implements the process if the request is not validated
     * @return void
     */
    abstract protected function noValidateProcess(): void;

    /**
     * This method implements the post-processing if the form is bound and valid.
     * The return value will be available as process() return (should be falsy on failure)
     *
     * @return bool
     */
    abstract protected function onSuccess(): bool;

    /**
     * Validates if the request can be accepted
     */
    protected function isValidateRequest(): bool
    {
        return array_search($this->request->getMethod(), $this->getValidMethods()) !== false;
    }

    protected function isValidForm(): bool
    {
        return $this->form->isValid();
    }

    /**
     * {@inheritdoc}
     */
    public function process($object = null, bool $forceHandleRequest = false): bool
    {
        $this->preProcess();
        if (!$this->isValidateRequest()) {
            $this->setObject($object);
            $this->noValidateProcess();
            if (!(null === $this->getObject())) {
                $this->getForm()->setData($this->getObject());
            }
        } else {
            if ($forceHandleRequest) { $this->form->handleRequest($this->request); }
            if ($this->isValidForm()) {
                return $this->onSuccess();
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function setManager(ManagerInterface $manager)
    {
        $this->manager = $manager;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function setObject($modelData)
    {
        $this->modelData = $modelData;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject()
    {
        return $this->modelData;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrors()
    {
        return self::getFormErrors($this->form);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public static function getFormErrors(FormInterface $form)
    {
        $errorArray = [];
        $errors = $form->getErrors(true, true);
        foreach ($errors as $error) {
            //clean if necessary
            $errorArray[] = $error;
        }
        return $errorArray;
    }
}
