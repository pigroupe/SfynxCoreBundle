<?php
namespace Sfynx\CoreBundle\Layers\Domain\ValueObject\Generalisation\Traits;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Sfynx\ToolBundle\Util\PiDateManager;

/**
 * trait class for birthday attributs.
 *
 * @category   Sfynx\CoreBundle\Layers
 * @package    Domain
 * @subpackage ValueObject\Generalisation\Traits
 */
trait TraitBirthday
{
    /**
     * @var string $birthday
     *
     * @Assert\Date(groups={"personal_data"}, message="user.birthday.date")
     * @ORM\Column(name="birthday", type="string", nullable = true)
     */
    protected $birthday;

    /**
     * Get the [optionally formatted] temporal [birthday] column value.
     *
     *
     * @param string $format The date/time format string (either date()-style or strftime()-style).
     *				 If format is null, then the raw DateTime object will be returned.
     *
     * @return mixed Formatted date/time value as string or DateTime object (if format is null), null if column is null, and 0 if column value is 0000-00-00
     * @throws \Exception - if unable to parse/validate the date/time value.
     */
    public function getBirthday($format = null)
    {
        if ($this->birthday === null) {
            return null;
        }
        if ($this->birthday === '0000-00-00') {
            // while technically this is not a default value of null,
            // this seems to be closest in meaning.
            return null;
        }
        try {
            $dt = new \DateTime($this->birthday);
        } catch (\Exception $x) {
            throw new \InvalidArgumentException("Internally stored date/time/timestamp value could not be converted to DateTime: " . var_export($this->birthday, true), $x);
        }
        if ($format === null) {
            return $dt;
        }
        if (strpos($format, '%') !== false) {
            return strftime($format, $dt->format('U'));
        }
        return $dt->format($format);
    }

    /**
     * Sets the value of [birthday] column to a normalized version of the date/time value specified.
     *
     * @param $currentbirthday
     * @param mixed $v string, integer (timestamp), or DateTime value.
     *               Empty strings are treated as null.
     *
     * @return User The current object (for fluent API support)
     */
    public static function setBirthday($currentbirthday, $v)
    {
        if ($currentbirthday !== null || $v !== null) {
            $dt = PiDateManager::newInstance($v, null, 'DateTime');
            $currentDateAsString = ($currentbirthday !== null && $tmpDt = new \DateTime($currentbirthday)) ? $tmpDt->format('Y-m-d') : null;
            $newDateAsString = $v ? $dt->format('Y-m-d') : null;
            if ($currentDateAsString !== $newDateAsString) {
                return $newDateAsString;
            }
        }
    }
}
