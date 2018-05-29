<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

/**
 * trait class for Slugify attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitSlugify
{
    /**
     * Get slug
     *
     * @return text
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param $text
     * @return mixed|string
     */
    public static function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace_callback(
            '#[^\\pL\/\d]+#u',
            function($matches) {
                return "-";
            },
            $text
        );
        // delete all accent
        $translit = ['Á'=>'A','À'=>'A','Â'=>'A','Ä'=>'A','Ã'=>'A','Å'=>'A','Ç'=>'C','É'=>'E','È'=>'E','Ê'=>'E','Ë'=>'E','Í'=>'I','Ï'=>'I','Î'=>'I','Ì'=>'I','Ñ'=>'N','Ó'=>'O','Ò'=>'O','Ô'=>'O','Ö'=>'O','Õ'=>'O','Ú'=>'U','Ù'=>'U','Û'=>'U','Ü'=>'U','Ý'=>'Y','á'=>'a','à'=>'a','â'=>'a','ä'=>'a','ã'=>'a','å'=>'a','ç'=>'c','é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','í'=>'i','ì'=>'i','î'=>'i','ï'=>'i','ñ'=>'n','ó'=>'o','ò'=>'o','ô'=>'o','ö'=>'o','õ'=>'o','ú'=>'u','ù'=>'u','û'=>'u','ü'=>'u','ý'=>'y','ÿ'=>'y'];
        $text     = strtr($text, $translit);
        $text     = self::wd_remove_accents($text, 'utf-8');
        // trim
        $text = trim($text, '-');
        // lowercase
        $text = strtolower($text);
        // remove unwanted characters
        if (empty($text)) {
            return '';
        }
        return $text;
    }

    /**
     * @param $str
     * @param string $charset
     * @return mixed|string
     */
    public static function wd_remove_accents($str, $charset='utf-8')
    {
        $str = htmlentities($str, ENT_NOQUOTES, $charset);
        $str = preg_replace('#&([A-za-z])(?:acute|cedil|caron|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str);
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str); // pour les ligatures e.g. '&oelig;'
        $str = preg_replace('#&[^;]+;#', '', $str); // supprime les autres caractères
        return $str;
    }
}
