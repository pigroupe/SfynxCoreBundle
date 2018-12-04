<?php
namespace Sfynx\CoreBundle\Layers\Application\Command;

use Sfynx\CoreBundle\Layers\Application\Command\Generalisation\AbstractCommand;

/**
 * Class EnabledajaxCommand.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Command
 */
class EnabledajaxCommand extends AbstractCommand
{
    /** @var mixed $id */
    protected $id;
    /** @var mixed $position */
    protected $position;

    /**
     * EnabledajaxCommand constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->id = $options['id'];
        $this->position = $options['position'];
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
    public function getPosition()
    {
        return $this->position;
    }
}
