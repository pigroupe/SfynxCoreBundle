<?php
namespace Sfynx\CoreBundle\Layers\Domain\Model\Interfaces;

/**
 * TraitSlugify Interface
 *
 */
interface TraitSlugifyInterface
{
    /**
     * Set $position
     *
     * @param string $text
     */
    public static function slugify($text);

    /**
     * @param $str
     * @param string $charset
     * @return mixed|string
     */
    public static function wd_remove_accents($str, $charset='utf-8');
}
