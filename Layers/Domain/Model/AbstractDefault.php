<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Sfynx\CoreBundle\Layers\Domain\Model\AbstractTranslation;
use Sfynx\CoreBundle\Layers\Domain\Model\Traits;

/**
 * abstract class for default attribut.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage Model
 * @abstract
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 * @since 2012-03-22
 */
abstract class AbstractDefault extends AbstractTranslation 
{
    use Traits\TraitHeritage;
    use Traits\TraitPosition;
    use Traits\TraitDatetime;
    use Traits\TraitEnabled;

    /**
     * @ORM\Column(name="position", type="integer",  nullable=true)
     */
    protected $position;  

    /**
     * Constructor
     */    
    public function __construct()
    {
        parent::__construct();
        
        $this->setEnabled(true);
    }  
    
    /**
     * Set id
     *
     * @return integer
     */
    public function setId($id)
    {
    	$this->id = (int) $id;
    }    
}