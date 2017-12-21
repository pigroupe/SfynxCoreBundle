<?php
namespace Sfynx\CoreBundle\Layers\Application\Query;

use Sfynx\CoreBundle\Layers\Application\Query\Generalisation\AbstractQuery;

/**
 * Class IndexQuery.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Application
 * @subpackage Query
 */
class IndexQuery extends AbstractQuery
{
    /** @var string */
    public $locale;
    /** @var string */
    public $category = '';
    /** @var boolean */
    public $NoLayout = false;
    /** @var boolean */
    public $isServerSide = false;
    /** @var int */
    public $sEcho = '';
}
