<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\Table;

/**
 * Table set
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\Output
 * 
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Table
{
    /**
     * @param $table
     * @param $key
     * @param $value
     */
    public static function table_set($keys, $value, &$table, &$level)
    {
        if(is_array($keys) && sizeof($keys) > 0) {
            $key = array_shift($keys);
            $level++;
            self::table_set($keys, $value, $table[$key], $level);

            return;
        }
        $table = $value;

        return $value;
    }

    /**
     * @param array $arr
     * @return array
     */
    public static function optimize(array $arr)
    {
        if (count($arr) == 1) {
            return self::optimize(reset($arr));
        }

        return $arr;
    }

    /**
     * @param array $data
     * @return string
     */
    public static function writeArray(array $data): string
    {
        return var_export($data, true);
    }
}
