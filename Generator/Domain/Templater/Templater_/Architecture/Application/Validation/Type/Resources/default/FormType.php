namespace <?php echo $templater->getTargetNamespace(); ?>;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as TypeCore;
use Symfony\Bridge\Doctrine\Form\Type as TypeForm;
use Doctrine\ORM\EntityRepository;
use Sfynx\CoreBundle\Layers\Application\Validation\Type\AbstractDefaultType;

/**
 * Class <?php echo $templater->getTargetClassname(); ?> <?php echo PHP_EOL ?>
 *
 * @category <?php echo $templater->getNamespace(); ?> <?php echo PHP_EOL ?>
 * @package Application
 * @subpackage <?php echo str_replace($templater->getNamespace() . '\Application\\', '', $templater->getTargetNamespace()); ?> <?php echo PHP_EOL ?>
 * @author SFYNX <contact@pi-groupe.net> <?php echo PHP_EOL ?>
 * @licence LGPL
 */
class <?php echo $templater->getTargetClassname(); ?> extends AbstractDefaultType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
    <?php if ($field->type == 'id'): ?>
        $<?php echo $field->name; ?> = $this->data_form['<?php echo $field->name; ?>List'];
    <?php endif; ?>
<?php endforeach; ?>

        $builder
<?php foreach ($templater->getTargetCommandFields() as $field): ?>
            <?php echo $templater->add($field->name, $field->type, $field) ?>
<?php endforeach; ?>
            <?php echo $templater->add('save', 'submit') ?>
            <?php echo $templater->add('saveAndContinue', 'submit') ?>
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => $this->data_form['data_class'],
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '<?php echo $templater->reportDir; ?>_<?php echo $templater->getTargetClassname(); ?>';
    }
}
