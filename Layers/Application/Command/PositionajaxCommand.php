<?php
namespace Sfynx\CoreBundle\Layers\Application\Command;

/**
 * Class PositionajaxCommand.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command
 */
class PositionajaxCommand
{
    /** @var mixed $id */
    protected $id;
    /** @var mixed $toPosition */
    protected $toPosition;

    public function __construct(array $options)
    {
        $this->id = $options['id'];
        $this->toPosition = $options['toPosition'];
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getToPosition()
    {
        return $this->toPosition;
    }
}
