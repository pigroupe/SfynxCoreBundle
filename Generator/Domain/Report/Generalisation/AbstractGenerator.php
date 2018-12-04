<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report\Generalisation;

use stdClass;
use Sfynx\CoreBundle\Generator\Domain\Report\Generalisation\Interfaces\GeneratorInterface;

/**
 * Class AbstractGenerator
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Templater\Generalisation
 * @abstract
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
abstract class AbstractGenerator implements GeneratorInterface
{
    /** @var stdClass */
    public static $dataCl = null;
    /** @var array */
    public static $dataArr = [];

    /**
     * Scripts constructor.
     */
    public function __construct()
    {
        if (null === static::$dataCl) {
            static::$dataCl = new stdClass;
        }
        if ([] === static::$dataArr) {
            static::$dataArr = [];
        }
    }

    /**
     * @inheritdoc
     */
    public static function convertDataToObject(): void
    {
        static::$dataCl = self::transform(static::$dataArr, false);
    }

    /**
     * @inheritdoc
     */
    public static function transform($data, $option = true)
    {
        return json_decode(json_encode($data), $option);
    }

    /**
     * @return stdClass
     */
    public static function intitializeDataCl(): void
    {
        AbstractGenerator::$dataCl = [];
    }

    /**
     * @return array
     */
    public static function intitializeDataArr(): void
    {
        AbstractGenerator::$dataArr = [];
    }
}
