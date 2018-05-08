<?php
namespace Sfynx\CoreBundle\Generator\Domain\Component\File;

/**
 * File copy
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Component\File
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class RecurseCopy
{
    /**
     * @param $src
     * @param $dst
     * @return void
     */
    public static function run($src, $dst): void
    {
        // test if $src directory is not empty
        if (!self::isSatisfiedByDirEmpty($src)) {
            // create $dst directory if not exist
            self::createIfNotExist($dst);
            // copy all files in destination directory
            self::handle($src, $dst);
        }
    }

    /**
     * @param string $dir
     * @return bool
     */
    public static function isSatisfiedByDirEmpty(string $dir): bool
    {
        return !(new \FilesystemIterator($dir))->valid();
    }

    /**
     * create $dst directory if not exist
     *
     * @param string $dir
     * @return void
     */
    public static function createIfNotExist(string $dir): void
    {
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    /**
     * copy all files in destination directory
     *
     * @param string $dir
     * @param string $dst
     * @return void
     */
    public static function handle(string $src, string $dst): void
    {
        $dir = opendir($src);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    self::run($src . '/' . $file, $dst . '/' . $file);
                } else {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
}
