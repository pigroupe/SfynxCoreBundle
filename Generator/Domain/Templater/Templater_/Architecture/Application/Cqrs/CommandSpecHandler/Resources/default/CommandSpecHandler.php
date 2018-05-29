namespace <?php echo $templater->getTargetNamespace(); ?>;

use Sfynx\SpecificationBundle\Specification\Generalisation\InterfaceSpecification;
use Sfynx\SpecificationBundle\Specification\Logical\XorSpec;
use Sfynx\SpecificationBundle\Specification\Logical\TrueSpec;
use Sfynx\CoreBundle\Layers\Application\Command\Validation\SpecHandler\Generalisation\AbstractCommandSpecHandler;

/**
 * Class <?php echo $templater->getTargetClassname(); ?><?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?><?php echo PHP_EOL ?>
 * @package Application
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Application\\', '', $templater->getTargetNamespace()); ?><?php echo PHP_EOL ?>
 *
 * @author SFYNX <sfynx@pi-groupe.net>
 * @link http://www.sfynx.fr
 * @license LGPL (https://opensource.org/licenses/LGPL-3.0)
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractCommandSpecHandler
{
    /**
     * @return XorSpec
     */
    public function initSpecifications(): InterfaceSpecification
    {
        return new TrueSpec();
    }
}
